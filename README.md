# 📚stackexchangemcp📚

## php

```sh
composer require vielhuber/stackexchangemcp
```

```php
use vielhuber\stackexchangemcp\stackexchangemcp;

$stack = new stackexchangemcp();
$stack->sites();
$stack->me(site: 'stackoverflow');
$stack->usersByIds(ids: '123', site: 'stackoverflow');
$stack->timelineOnUsers(ids: '123', site: 'stackoverflow');
$stack->postsOnUsers(ids: '123', site: 'stackoverflow');
$stack->questions(site: 'stackoverflow');
$stack->questionsByIds(ids: '123', site: 'stackoverflow');
$stack->answersOnQuestions(ids: '123', site: 'stackoverflow');
$stack->answersByIds(ids: '123', site: 'stackoverflow');
$stack->commentsOnPosts(ids: '123', site: 'stackoverflow');
$stack->commentsByIds(ids: '123', site: 'stackoverflow');
$stack->createQuestion(site: 'stackoverflow', parameters: $parameters);
$stack->editQuestion(id: '123', site: 'stackoverflow', parameters: $parameters);
$stack->deleteQuestion(id: '123', site: 'stackoverflow');
$stack->createAnswer(id: '123', site: 'stackoverflow', parameters: $parameters);
$stack->editAnswer(id: '123', site: 'stackoverflow', parameters: $parameters);
$stack->deleteAnswer(id: '123', site: 'stackoverflow');
$stack->createComment(id: '123', site: 'stackoverflow', parameters: $parameters);
$stack->editComment(id: '123', site: 'stackoverflow', parameters: $parameters);
$stack->deleteComment(id: '123', site: 'stackoverflow');
$stack->advancedSearch(site: 'stackoverflow');
$stack->tags(site: 'stackoverflow');
$stack->meTimeline(site: 'stackoverflow');
```

public reads need no token. writes: [register an app](https://stackapps.com/apps/oauth), authorize `write_access` and set `STACKEXCHANGE_API_KEY` / `STACKEXCHANGE_ACCESS_TOKEN`. oauth pkce: `$authorization->url()` / `exchangeCode()`. `site` supports the whole network, including meta. `$parameters` uses the official request schema.

## mcp

```json
{
    "mcpServers": {
        "stack": {
            "command": "php",
            "args": ["/absolute/path/to/vendor/bin/mcp-server.php"],
            "env": {
                "STACKEXCHANGE_API_KEY": "your-api-key",
                "STACKEXCHANGE_ACCESS_TOKEN": "your-access-token",
                "STACKEXCHANGE_SITE": "stackoverflow"
            }
        }
    }
}
```

stdio is private. http requires https and `MCP_TOKEN`.

[api documentation](https://api.stackexchange.com/docs)
