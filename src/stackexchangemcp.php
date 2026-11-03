<?php
declare(strict_types=1);

namespace vielhuber\stackexchangemcp;

use vielhuber\simplemcp\Attributes\McpTool;
use vielhuber\simplemcp\Attributes\Schema;

final class stackexchangemcp
{
    private readonly Client $client;

    /**
     * Select any API-supported main or Meta site without changing the tool surface.
     */
    public function __construct(
        #[\SensitiveParameter] ?string $apiKey = null,
        #[\SensitiveParameter] ?string $accessToken = null,
        ?string $site = null
    ) {
        $this->client = new Client(
            apiKey: $apiKey ?? (getenv('STACKEXCHANGE_API_KEY') ?: $_ENV['STACKEXCHANGE_API_KEY'] ?? ''),
            accessToken: $accessToken ??
                (getenv('STACKEXCHANGE_ACCESS_TOKEN') ?: $_ENV['STACKEXCHANGE_ACCESS_TOKEN'] ?? ''),
            defaultSite: $site ?? (getenv('STACKEXCHANGE_SITE') ?: $_ENV['STACKEXCHANGE_SITE'] ?? 'stackoverflow')
        );
    }
    /**
     * Get all the sites in the Stack Exchange network.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(description: 'Get all the sites in the Stack Exchange network. https://api.stackexchange.com/docs/sites')]
    public function sites(
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'page' => ['type' => 'integer'],
                    'pagesize' => ['type' => 'integer'],
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'GET',
            path: '/sites',
            backoffMethod: 'GET /sites',
            parameters: $parameters
        );
    }

    /**
     * Get the users identified by a set of ids.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(description: 'Get the users identified by a set of ids. https://api.stackexchange.com/docs/me')]
    public function me(
        ?string $site = null,
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'page' => ['type' => 'integer'],
                    'pagesize' => ['type' => 'integer'],
                    'fromdate' => ['type' => 'integer'],
                    'todate' => ['type' => 'integer'],
                    'order' => ['type' => 'string', 'enum' => ['desc', 'asc']],
                    'min' => ['type' => ['integer', 'number', 'string']],
                    'max' => ['type' => ['integer', 'number', 'string']],
                    'sort' => ['type' => 'string', 'enum' => ['reputation', 'creation', 'name', 'modified']],
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'GET',
            path: '/me',
            backoffMethod: 'GET /users/{ids}',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true
        );
    }

    /**
     * Get the users identified by a set of ids.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(description: 'Get the users identified by a set of ids. https://api.stackexchange.com/docs/users-by-ids')]
    public function usersByIds(
        string $ids,
        ?string $site = null,
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'page' => ['type' => 'integer'],
                    'pagesize' => ['type' => 'integer'],
                    'fromdate' => ['type' => 'integer'],
                    'todate' => ['type' => 'integer'],
                    'order' => ['type' => 'string', 'enum' => ['desc', 'asc']],
                    'min' => ['type' => ['integer', 'number', 'string']],
                    'max' => ['type' => ['integer', 'number', 'string']],
                    'sort' => ['type' => 'string', 'enum' => ['reputation', 'creation', 'name', 'modified']],
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'GET',
            path: '/users/' . $this->client->segment($ids),
            backoffMethod: 'GET /users/{ids}',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get a subset of the actions of that have been taken by the users identified by a set of ids.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get a subset of the actions of that have been taken by the users identified by a set of ids. https://api.stackexchange.com/docs/timeline-on-users'
    )]
    public function timelineOnUsers(
        string $ids,
        ?string $site = null,
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'page' => ['type' => 'integer'],
                    'pagesize' => ['type' => 'integer'],
                    'fromdate' => ['type' => 'integer'],
                    'todate' => ['type' => 'integer'],
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'GET',
            path: '/users/' . $this->client->segment($ids) . '/timeline',
            backoffMethod: 'GET /users/{ids}/timeline',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get all posts (questions and answers) owned by a set of users.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get all posts (questions and answers) owned by a set of users. https://api.stackexchange.com/docs/posts-on-users'
    )]
    public function postsOnUsers(
        string $ids,
        ?string $site = null,
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'page' => ['type' => 'integer'],
                    'pagesize' => ['type' => 'integer'],
                    'fromdate' => ['type' => 'integer'],
                    'todate' => ['type' => 'integer'],
                    'order' => ['type' => 'string', 'enum' => ['desc', 'asc']],
                    'min' => ['type' => ['integer', 'number', 'string']],
                    'max' => ['type' => ['integer', 'number', 'string']],
                    'sort' => ['type' => 'string', 'enum' => ['activity', 'creation', 'votes']],
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'GET',
            path: '/users/' . $this->client->segment($ids) . '/posts',
            backoffMethod: 'GET /users/{ids}/posts',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get all questions on the site.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(description: 'Get all questions on the site. https://api.stackexchange.com/docs/questions')]
    public function questions(
        ?string $site = null,
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'page' => ['type' => 'integer'],
                    'pagesize' => ['type' => 'integer'],
                    'fromdate' => ['type' => 'integer'],
                    'todate' => ['type' => 'integer'],
                    'order' => ['type' => 'string', 'enum' => ['desc', 'asc']],
                    'min' => ['type' => ['integer', 'number', 'string']],
                    'max' => ['type' => ['integer', 'number', 'string']],
                    'sort' => ['type' => 'string', 'enum' => ['activity', 'votes', 'creation', 'hot', 'week', 'month']],
                    'tagged' => ['type' => 'array', 'items' => ['type' => 'string']],
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'GET',
            path: '/questions',
            backoffMethod: 'GET /questions',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get the questions identified by a set of ids.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get the questions identified by a set of ids. https://api.stackexchange.com/docs/questions-by-ids'
    )]
    public function questionsByIds(
        string $ids,
        ?string $site = null,
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'page' => ['type' => 'integer'],
                    'pagesize' => ['type' => 'integer'],
                    'fromdate' => ['type' => 'integer'],
                    'todate' => ['type' => 'integer'],
                    'order' => ['type' => 'string', 'enum' => ['desc', 'asc']],
                    'min' => ['type' => ['integer', 'number', 'string']],
                    'max' => ['type' => ['integer', 'number', 'string']],
                    'sort' => ['type' => 'string', 'enum' => ['activity', 'votes', 'creation']],
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'GET',
            path: '/questions/' . $this->client->segment($ids),
            backoffMethod: 'GET /questions/{ids}',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get the answers to the questions identified by a set of ids.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get the answers to the questions identified by a set of ids. https://api.stackexchange.com/docs/answers-on-questions'
    )]
    public function answersOnQuestions(
        string $ids,
        ?string $site = null,
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'page' => ['type' => 'integer'],
                    'pagesize' => ['type' => 'integer'],
                    'fromdate' => ['type' => 'integer'],
                    'todate' => ['type' => 'integer'],
                    'order' => ['type' => 'string', 'enum' => ['desc', 'asc']],
                    'min' => ['type' => ['integer', 'number', 'string']],
                    'max' => ['type' => ['integer', 'number', 'string']],
                    'sort' => ['type' => 'string', 'enum' => ['activity', 'creation', 'votes']],
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'GET',
            path: '/questions/' . $this->client->segment($ids) . '/answers',
            backoffMethod: 'GET /questions/{ids}/answers',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get answers identified by a set of ids.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(description: 'Get answers identified by a set of ids. https://api.stackexchange.com/docs/answers-by-ids')]
    public function answersByIds(
        string $ids,
        ?string $site = null,
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'page' => ['type' => 'integer'],
                    'pagesize' => ['type' => 'integer'],
                    'fromdate' => ['type' => 'integer'],
                    'todate' => ['type' => 'integer'],
                    'order' => ['type' => 'string', 'enum' => ['desc', 'asc']],
                    'min' => ['type' => ['integer', 'number', 'string']],
                    'max' => ['type' => ['integer', 'number', 'string']],
                    'sort' => ['type' => 'string', 'enum' => ['activity', 'creation', 'votes']],
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'GET',
            path: '/answers/' . $this->client->segment($ids),
            backoffMethod: 'GET /answers/{ids}',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get comments on the posts (question or answer) identified by a set of ids.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get comments on the posts (question or answer) identified by a set of ids. https://api.stackexchange.com/docs/comments-on-posts'
    )]
    public function commentsOnPosts(
        string $ids,
        ?string $site = null,
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'page' => ['type' => 'integer'],
                    'pagesize' => ['type' => 'integer'],
                    'fromdate' => ['type' => 'integer'],
                    'todate' => ['type' => 'integer'],
                    'order' => ['type' => 'string', 'enum' => ['desc', 'asc']],
                    'min' => ['type' => ['integer', 'number', 'string']],
                    'max' => ['type' => ['integer', 'number', 'string']],
                    'sort' => ['type' => 'string', 'enum' => ['creation', 'votes']],
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'GET',
            path: '/posts/' . $this->client->segment($ids) . '/comments',
            backoffMethod: 'GET /posts/{ids}/comments',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get comments identified by a set of ids.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get comments identified by a set of ids. https://api.stackexchange.com/docs/comments-by-ids'
    )]
    public function commentsByIds(
        string $ids,
        ?string $site = null,
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'page' => ['type' => 'integer'],
                    'pagesize' => ['type' => 'integer'],
                    'fromdate' => ['type' => 'integer'],
                    'todate' => ['type' => 'integer'],
                    'order' => ['type' => 'string', 'enum' => ['desc', 'asc']],
                    'min' => ['type' => ['integer', 'number', 'string']],
                    'max' => ['type' => ['integer', 'number', 'string']],
                    'sort' => ['type' => 'string', 'enum' => ['creation', 'votes']],
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'GET',
            path: '/comments/' . $this->client->segment($ids),
            backoffMethod: 'GET /comments/{ids}',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Write operation. Creates a new question.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Write operation. Creates a new question. https://api.stackexchange.com/docs/create-question'
    )]
    public function createQuestion(
        ?string $site = null,
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'title' => ['type' => 'string'],
                    'body' => ['type' => 'string'],
                    'tags' => ['type' => 'array', 'items' => ['type' => 'string']],
                    'preview' => ['type' => 'boolean'],
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'POST',
            path: '/questions/add',
            backoffMethod: 'POST /questions/add',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true,
            write: true
        );
    }

    /**
     * Write operation. Edits the given question.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Write operation. Edits the given question. https://api.stackexchange.com/docs/edit-question'
    )]
    public function editQuestion(
        int $id,
        ?string $site = null,
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'title' => ['type' => 'string'],
                    'body' => ['type' => 'string'],
                    'tags' => ['type' => 'array', 'items' => ['type' => 'string']],
                    'comment' => ['type' => 'string'],
                    'preview' => ['type' => 'boolean'],
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'POST',
            path: '/questions/' . $this->client->segment((string) $id) . '/edit',
            backoffMethod: 'POST /questions/{id}/edit',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true,
            write: true
        );
    }

    /**
     * Write operation. Deletes the given question.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Write operation. Deletes the given question. https://api.stackexchange.com/docs/delete-question'
    )]
    public function deleteQuestion(
        int $id,
        ?string $site = null,
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'preview' => ['type' => 'boolean'],
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'POST',
            path: '/questions/' . $this->client->segment((string) $id) . '/delete',
            backoffMethod: 'POST /questions/{id}/delete',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true,
            write: true
        );
    }

    /**
     * Write operation. Creates an answer on the given question.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Write operation. Creates an answer on the given question. https://api.stackexchange.com/docs/create-answer'
    )]
    public function createAnswer(
        int $id,
        ?string $site = null,
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'body' => ['type' => 'string'],
                    'preview' => ['type' => 'boolean'],
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'POST',
            path: '/questions/' . $this->client->segment((string) $id) . '/answers/add',
            backoffMethod: 'POST /questions/{id}/answers/add',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true,
            write: true
        );
    }

    /**
     * Write operation. Edits the given answer.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(description: 'Write operation. Edits the given answer. https://api.stackexchange.com/docs/edit-answer')]
    public function editAnswer(
        int $id,
        ?string $site = null,
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'body' => ['type' => 'string'],
                    'comment' => ['type' => 'string'],
                    'preview' => ['type' => 'boolean'],
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'POST',
            path: '/answers/' . $this->client->segment((string) $id) . '/edit',
            backoffMethod: 'POST /answers/{id}/edit',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true,
            write: true
        );
    }

    /**
     * Write operation. Deletes the given answer.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Write operation. Deletes the given answer. https://api.stackexchange.com/docs/delete-answer'
    )]
    public function deleteAnswer(
        int $id,
        ?string $site = null,
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'preview' => ['type' => 'boolean'],
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'POST',
            path: '/answers/' . $this->client->segment((string) $id) . '/delete',
            backoffMethod: 'POST /answers/{id}/delete',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true,
            write: true
        );
    }

    /**
     * Write operation. Create a new comment on the post identified by id.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Write operation. Create a new comment on the post identified by id. https://api.stackexchange.com/docs/create-comment'
    )]
    public function createComment(
        int $id,
        ?string $site = null,
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'body' => ['type' => 'string'],
                    'preview' => ['type' => 'boolean'],
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'POST',
            path: '/posts/' . $this->client->segment((string) $id) . '/comments/add',
            backoffMethod: 'POST /posts/{id}/comments/add',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true,
            write: true
        );
    }

    /**
     * Write operation. Edit a comment identified by its id.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Write operation. Edit a comment identified by its id. https://api.stackexchange.com/docs/edit-comment'
    )]
    public function editComment(
        int $id,
        ?string $site = null,
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'body' => ['type' => 'string'],
                    'preview' => ['type' => 'boolean'],
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'POST',
            path: '/comments/' . $this->client->segment((string) $id) . '/edit',
            backoffMethod: 'POST /comments/{id}/edit',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true,
            write: true
        );
    }

    /**
     * Write operation. Delete a comment identified by its id.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Write operation. Delete a comment identified by its id. https://api.stackexchange.com/docs/delete-comment'
    )]
    public function deleteComment(
        int $id,
        ?string $site = null,
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'preview' => ['type' => 'boolean'],
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'POST',
            path: '/comments/' . $this->client->segment((string) $id) . '/delete',
            backoffMethod: 'POST /comments/{id}/delete',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true,
            write: true
        );
    }

    /**
     * Search the site for questions using most of the on-site search options.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Search the site for questions using most of the on-site search options. https://api.stackexchange.com/docs/advanced-search'
    )]
    public function advancedSearch(
        ?string $site = null,
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'page' => ['type' => 'integer'],
                    'pagesize' => ['type' => 'integer'],
                    'fromdate' => ['type' => 'integer'],
                    'todate' => ['type' => 'integer'],
                    'order' => ['type' => 'string', 'enum' => ['desc', 'asc']],
                    'min' => ['type' => ['integer', 'number', 'string']],
                    'max' => ['type' => ['integer', 'number', 'string']],
                    'sort' => ['type' => 'string', 'enum' => ['activity', 'votes', 'creation', 'relevance']],
                    'q' => ['type' => 'string'],
                    'accepted' => ['type' => 'string', 'enum' => ['', 'True', 'False']],
                    'answers' => ['type' => 'integer'],
                    'body' => ['type' => 'string'],
                    'closed' => ['type' => 'string', 'enum' => ['', 'True', 'False']],
                    'migrated' => ['type' => 'string', 'enum' => ['', 'True', 'False']],
                    'notice' => ['type' => 'string', 'enum' => ['', 'True', 'False']],
                    'nottagged' => ['type' => 'array', 'items' => ['type' => 'string']],
                    'tagged' => ['type' => 'array', 'items' => ['type' => 'string']],
                    'title' => ['type' => 'string'],
                    'user' => ['type' => 'integer'],
                    'url' => ['type' => 'string'],
                    'views' => ['type' => 'integer'],
                    'wiki' => ['type' => 'string', 'enum' => ['', 'True', 'False']],
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'GET',
            path: '/search/advanced',
            backoffMethod: 'GET /search/advanced',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get the tags on the site.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(description: 'Get the tags on the site. https://api.stackexchange.com/docs/tags')]
    public function tags(
        ?string $site = null,
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'page' => ['type' => 'integer'],
                    'pagesize' => ['type' => 'integer'],
                    'fromdate' => ['type' => 'integer'],
                    'todate' => ['type' => 'integer'],
                    'order' => ['type' => 'string', 'enum' => ['desc', 'asc']],
                    'min' => ['type' => ['integer', 'number', 'string']],
                    'max' => ['type' => ['integer', 'number', 'string']],
                    'sort' => ['type' => 'string', 'enum' => ['popular', 'activity', 'name']],
                    'inname' => ['type' => 'string'],
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'GET',
            path: '/tags',
            backoffMethod: 'GET /tags',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get a subset of the actions of that have been taken by the users identified by a set of ids.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get a subset of the actions of that have been taken by the users identified by a set of ids. https://api.stackexchange.com/docs/me-timeline'
    )]
    public function meTimeline(
        ?string $site = null,
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'page' => ['type' => 'integer'],
                    'pagesize' => ['type' => 'integer'],
                    'fromdate' => ['type' => 'integer'],
                    'todate' => ['type' => 'integer'],
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'GET',
            path: '/me/timeline',
            backoffMethod: 'GET /users/{ids}/timeline',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true
        );
    }
}
