import json, re
from pathlib import Path

root = Path(__file__).resolve().parent.parent
package = json.loads((root / 'composer.json').read_text())['name'].split('/')[1]
catalog = json.loads((root / 'resources/endpoints.json').read_text())

def literal(value):
    if value is None: return 'null'
    if value is True: return 'true'
    if value is False: return 'false'
    if isinstance(value, (int,float)): return str(value)
    if isinstance(value,str): return "'" + value.replace('\\','\\\\').replace("'","\\'") + "'"
    if isinstance(value,list): return '[' + ', '.join(literal(item) for item in value) + ']'
    return '[' + ', '.join(literal(key) + ' => ' + literal(item) for key,item in value.items()) + ']'

def camel(value):
    words=re.split(r'[^A-Za-z0-9]+',value)
    return words[0]+''.join(word[:1].upper()+word[1:] for word in words[1:])

methods=[]
for endpoint in catalog:
    name=camel(endpoint['name'])
    arguments=[]
    path=literal(endpoint['path'])
    for field in re.findall(r'\{([^}]+)\}',endpoint['path']):
        if field=='accessTokens' and package=='stackexchangemcp':
            replacement='$this->client->encodedAccessToken()'
        else:
            variable=camel(field)
            parameter_type=endpoint.get('pathTypes',{}).get(field,'string')
            arguments.append(f'{parameter_type} ${variable}')
            value=f'(string) ${variable}' if parameter_type=='int' else f'${variable}'
            if package=='chessmcp' and field=='username': value=f'strtolower(${variable})'
            if package=='chessmcp' and field in ['country','title']: value=f'strtoupper(${variable})'
            if package=='chessmcp' and field=='month': value=f'sprintf("%02d", ${variable})'
            replacement=f'$this->client->segment({value})'
        path=path.replace('{'+field+'}',"' . "+replacement+" . '")
    path=path.replace(" . ''",'')
    if endpoint.get('site'):
        arguments.append('?string $site = null')
    if endpoint.get('parameters'):
        default='' if endpoint.get('parametersRequired') else ' = []'
        arguments.append('#[Schema(definition: '+literal(endpoint['parameters'])+')] array $parameters'+default)
    if 'body' in endpoint:
        default='' if endpoint.get('bodyRequired') else ' = []'
        arguments.append('#[Schema(definition: '+literal(endpoint['body'])+')] array $body'+default)
    if endpoint.get('bodyRequired') or endpoint.get('parametersRequired'):
        arguments.sort(key=lambda argument:' = ' in argument)
    description=endpoint['description'].rstrip('.')+'.'
    if endpoint.get('write'): description='Write operation. '+description
    result='string' if endpoint.get('text') else 'array'
    tags=[]
    if endpoint.get('parameters'): tags.append('@param array<string, mixed> $parameters')
    if 'body' in endpoint: tags.append('@param array<string, mixed> $body')
    if result!='string': tags.append('@return array<mixed>')
    doc=('\n     *\n     * '+'\n     * '.join(tags)) if tags else ''
    calls=['method: '+literal(endpoint['method']),'path: '+path]
    if package=='stackexchangemcp': calls.append('backoffMethod: '+literal(endpoint['backoffMethod']))
    if endpoint.get('parameters'):calls.append('parameters: $parameters')
    if 'body' in endpoint:calls.append('body: $body')
    if endpoint.get('site'):calls.append("site: $site ?? ''")
    if endpoint.get('auth'):calls.append('authenticated: true')
    if endpoint.get('write') and package=='stackexchangemcp':calls.append('write: true')
    if endpoint.get('text'):calls.append('text: true')
    methods.append('    /**\n     * '+description+doc+'\n     */\n    #[McpTool(description: '+literal(description+' '+endpoint['url'])+')]\n    public function '+name+'('+', '.join(arguments)+'): '+result+'\n    {\n        return $this->client->request('+', '.join(calls)+');\n    }\n')
header=(root/'resources/header.php.txt').read_text()
footer=(root/'resources/footer.php.txt').read_text() if (root/'resources/footer.php.txt').exists() else ''
(root/'src'/f'{package}.php').write_text(header+'\n'.join(methods)+footer+'}\n')
print(package+': generated '+str(len(methods))+' API methods')
