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
     * Get all answers on the site.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(description: 'Get all answers on the site. https://api.stackexchange.com/docs/answers')]
    public function answers(
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
            path: '/answers',
            backoffMethod: 'GET /answers',
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
     * Write operation. Casts an accept vote on the given answer.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Write operation. Casts an accept vote on the given answer. https://api.stackexchange.com/docs/accept-answer'
    )]
    public function acceptAnswer(
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
            path: '/answers/' . $this->client->segment((string) $id) . '/accept',
            backoffMethod: 'POST /answers/{id}/accept',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true,
            write: true
        );
    }

    /**
     * Write operation. Undoes an accept vote on the given answer.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Write operation. Undoes an accept vote on the given answer. https://api.stackexchange.com/docs/undo-accept-answer'
    )]
    public function undoAcceptAnswer(
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
            path: '/answers/' . $this->client->segment((string) $id) . '/accept/undo',
            backoffMethod: 'POST /answers/{id}/accept/undo',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true,
            write: true
        );
    }

    /**
     * Get comments on the answers identified by a set of ids.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get comments on the answers identified by a set of ids. https://api.stackexchange.com/docs/comments-on-answers'
    )]
    public function commentsOnAnswers(
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
            path: '/answers/' . $this->client->segment($ids) . '/comments',
            backoffMethod: 'GET /answers/{ids}/comments',
            parameters: $parameters,
            site: $site ?? ''
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
     * Write operation. Casts a downvote on the given answer.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Write operation. Casts a downvote on the given answer. https://api.stackexchange.com/docs/downvote-answer'
    )]
    public function downvoteAnswer(
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
            path: '/answers/' . $this->client->segment((string) $id) . '/downvote',
            backoffMethod: 'POST /answers/{id}/downvote',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true,
            write: true
        );
    }

    /**
     * Write operation. Undoes a downvote on the given answer.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Write operation. Undoes a downvote on the given answer. https://api.stackexchange.com/docs/undo-downvote-answer'
    )]
    public function undoDownvoteAnswer(
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
            path: '/answers/' . $this->client->segment((string) $id) . '/downvote/undo',
            backoffMethod: 'POST /answers/{id}/downvote/undo',
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
     * Returns valid flag options for the given answer.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Returns valid flag options for the given answer. https://api.stackexchange.com/docs/answer-flag-options'
    )]
    public function answerFlagOptions(
        int $id,
        ?string $site = null,
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'GET',
            path: '/answers/' . $this->client->segment((string) $id) . '/flags/options',
            backoffMethod: 'GET /answers/{id}/flags/options',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true
        );
    }

    /**
     * Write operation. Casts a flag on the given answer.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Write operation. Casts a flag on the given answer. https://api.stackexchange.com/docs/create-answer-flag'
    )]
    public function createAnswerFlag(
        int $id,
        ?string $site = null,
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'option_id' => ['type' => 'integer'],
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
            path: '/answers/' . $this->client->segment((string) $id) . '/flags/add',
            backoffMethod: 'POST /answers/{id}/flags/add',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true,
            write: true
        );
    }

    /**
     * Gets all questions the answers identified by ids are on.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Gets all questions the answers identified by ids are on. https://api.stackexchange.com/docs/questions-by-answer-ids'
    )]
    public function questionsByAnswerIds(
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
            path: '/answers/' . $this->client->segment($ids) . '/questions',
            backoffMethod: 'GET /answers/{ids}/questions',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Write operation. Casts an upvote on the given answer.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Write operation. Casts an upvote on the given answer. https://api.stackexchange.com/docs/upvote-answer'
    )]
    public function upvoteAnswer(
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
            path: '/answers/' . $this->client->segment((string) $id) . '/upvote',
            backoffMethod: 'POST /answers/{id}/upvote',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true,
            write: true
        );
    }

    /**
     * Write operation. Undoes an upvote on the given answer.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Write operation. Undoes an upvote on the given answer. https://api.stackexchange.com/docs/undo-upvote-answer'
    )]
    public function undoUpvoteAnswer(
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
            path: '/answers/' . $this->client->segment((string) $id) . '/upvote/undo',
            backoffMethod: 'POST /answers/{id}/upvote/undo',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true,
            write: true
        );
    }

    /**
     * Write operation. Casts a recommendation on the given answer.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Write operation. Casts a recommendation on the given answer. https://api.stackexchange.com/docs/recommend-answer'
    )]
    public function recommendAnswer(
        int $id,
        ?string $site = null,
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'slug' => ['type' => 'string'],
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
            path: '/answers/' . $this->client->segment((string) $id) . '/recommend',
            backoffMethod: 'POST /answers/{id}/recommend',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true,
            write: true
        );
    }

    /**
     * Write operation. Undoes an recommendation on the given answer.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Write operation. Undoes an recommendation on the given answer. https://api.stackexchange.com/docs/undo-recommend-answer'
    )]
    public function undoRecommendAnswer(
        int $id,
        ?string $site = null,
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'slug' => ['type' => 'string'],
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
            path: '/answers/' . $this->client->segment((string) $id) . '/recommend/undo',
            backoffMethod: 'POST /answers/{id}/recommend/undo',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true,
            write: true
        );
    }

    /**
     * Write operation. Creates a suggested edit on an existing answer.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Write operation. Creates a suggested edit on an existing answer. https://api.stackexchange.com/docs/create-answer-suggested-edit'
    )]
    public function createAnswerSuggestedEdit(
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
            path: '/answers/' . $this->client->segment((string) $id) . '/suggested-edit/add',
            backoffMethod: 'POST /answers/{id}/suggested-edit/add',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true,
            write: true
        );
    }

    /**
     * Get all badges on the site, in alphabetical order.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get all badges on the site, in alphabetical order. https://api.stackexchange.com/docs/badges'
    )]
    public function badges(
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
                    'sort' => ['type' => 'string', 'enum' => ['rank', 'name', 'type']],
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
            path: '/badges',
            backoffMethod: 'GET /badges',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get the badges identified by ids.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(description: 'Get the badges identified by ids. https://api.stackexchange.com/docs/badges-by-ids')]
    public function badgesByIds(
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
                    'sort' => ['type' => 'string', 'enum' => ['rank', 'name', 'type']],
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'GET',
            path: '/badges/' . $this->client->segment($ids),
            backoffMethod: 'GET /badges/{ids}',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get all non-tagged-based badges in alphabetical order.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get all non-tagged-based badges in alphabetical order. https://api.stackexchange.com/docs/badges-by-name'
    )]
    public function badgesByName(
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
                    'sort' => ['type' => 'string', 'enum' => ['rank', 'name']],
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
            path: '/badges/name',
            backoffMethod: 'GET /badges/name',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get badges recently awarded on the site.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get badges recently awarded on the site. https://api.stackexchange.com/docs/badge-recipients'
    )]
    public function badgeRecipients(
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
            path: '/badges/recipients',
            backoffMethod: 'GET /badges/recipients',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get the recent recipients of the given badges.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get the recent recipients of the given badges. https://api.stackexchange.com/docs/badge-recipients-by-ids'
    )]
    public function badgeRecipientsByIds(
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
            path: '/badges/' . $this->client->segment($ids) . '/recipients',
            backoffMethod: 'GET /badges/{ids}/recipients',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get all tagged-based badges in alphabetical order.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get all tagged-based badges in alphabetical order. https://api.stackexchange.com/docs/badges-by-tag'
    )]
    public function badgesByTag(
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
                    'sort' => ['type' => 'string', 'enum' => ['rank', 'name']],
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
            path: '/badges/tags',
            backoffMethod: 'GET /badges/tags',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get all Collectives on the site, in alphabetical order.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get all Collectives on the site, in alphabetical order. https://api.stackexchange.com/docs/collectives'
    )]
    public function collectives(
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
                    'sort' => ['type' => 'string', 'enum' => ['name']],
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'GET',
            path: '/collectives',
            backoffMethod: 'GET /collectives',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get Collectives identified by a set of slugs.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get Collectives identified by a set of slugs. https://api.stackexchange.com/docs/collectives-by-slug'
    )]
    public function collectivesBySlug(
        string $slugs,
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
                    'sort' => ['type' => 'string', 'enum' => ['name']],
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'GET',
            path: '/collectives/' . $this->client->segment($slugs),
            backoffMethod: 'GET /collectives/{slugs}',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get questions identified by a set of slugs.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get questions identified by a set of slugs. https://api.stackexchange.com/docs/questions-by-collective'
    )]
    public function questionsByCollective(
        string $slugs,
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
            path: '/collectives/' . $this->client->segment($slugs) . '/questions',
            backoffMethod: 'GET /collectives/{slugs}/questions',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get answers identified by a set of slugs.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get answers identified by a set of slugs. https://api.stackexchange.com/docs/answers-by-collective'
    )]
    public function answersByCollective(
        string $slugs,
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
            path: '/collectives/' . $this->client->segment($slugs) . '/answers',
            backoffMethod: 'GET /collectives/{slugs}/answers',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get tags identified by a set of slugs.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get tags identified by a set of slugs. https://api.stackexchange.com/docs/tags-by-collective'
    )]
    public function tagsByCollective(
        string $slugs,
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
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'GET',
            path: '/collectives/' . $this->client->segment($slugs) . '/tags',
            backoffMethod: 'GET /collectives/{slugs}/tags',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get users identified by a set of slugs.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get users identified by a set of slugs. https://api.stackexchange.com/docs/users-by-collective'
    )]
    public function usersByCollective(
        string $slugs,
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
            path: '/collectives/' . $this->client->segment($slugs) . '/users',
            backoffMethod: 'GET /collectives/{slugs}/users',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get all comments on the site.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(description: 'Get all comments on the site. https://api.stackexchange.com/docs/comments')]
    public function comments(
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
            path: '/comments',
            backoffMethod: 'GET /comments',
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
     * Write operation. Casts a flag on the given comment.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Write operation. Casts a flag on the given comment. https://api.stackexchange.com/docs/create-comment-flag'
    )]
    public function createCommentFlag(
        int $id,
        ?string $site = null,
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'option_id' => ['type' => 'integer'],
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
            path: '/comments/' . $this->client->segment((string) $id) . '/flags/add',
            backoffMethod: 'POST /comments/{id}/flags/add',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true,
            write: true
        );
    }

    /**
     * Returns valid flag options for the given comment.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Returns valid flag options for the given comment. https://api.stackexchange.com/docs/comment-flag-options'
    )]
    public function commentFlagOptions(
        int $id,
        ?string $site = null,
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'GET',
            path: '/comments/' . $this->client->segment((string) $id) . '/flags/options',
            backoffMethod: 'GET /comments/{id}/flags/options',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true
        );
    }

    /**
     * Write operation. Casts an upvote on the given comment.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Write operation. Casts an upvote on the given comment. https://api.stackexchange.com/docs/upvote-comment'
    )]
    public function upvoteComment(
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
            path: '/comments/' . $this->client->segment((string) $id) . '/upvote',
            backoffMethod: 'POST /comments/{id}/upvote',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true,
            write: true
        );
    }

    /**
     * Write operation. Undoes an upvote on the given comment.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Write operation. Undoes an upvote on the given comment. https://api.stackexchange.com/docs/undo-upvote-comment'
    )]
    public function undoUpvoteComment(
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
            path: '/comments/' . $this->client->segment((string) $id) . '/upvote/undo',
            backoffMethod: 'POST /comments/{id}/upvote/undo',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true,
            write: true
        );
    }

    /**
     * Get recent events that have occurred on the site. Effectively a stream of new users and content.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get recent events that have occurred on the site. Effectively a stream of new users and content. https://api.stackexchange.com/docs/events'
    )]
    public function events(
        ?string $site = null,
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'page' => ['type' => 'integer'],
                    'pagesize' => ['type' => 'integer'],
                    'since' => ['type' => 'integer'],
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'GET',
            path: '/events',
            backoffMethod: 'GET /events',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true
        );
    }

    /**
     * Get information about the entire site.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(description: 'Get information about the entire site. https://api.stackexchange.com/docs/info')]
    public function info(
        ?string $site = null,
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'GET',
            path: '/info',
            backoffMethod: 'GET /info',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get all posts (questions and answers) in the system.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get all posts (questions and answers) in the system. https://api.stackexchange.com/docs/posts'
    )]
    public function posts(
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
            path: '/posts',
            backoffMethod: 'GET /posts',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get all posts identified by a set of ids. Useful for when the type of post (question or answer) is not known.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get all posts identified by a set of ids. Useful for when the type of post (question or answer) is not known. https://api.stackexchange.com/docs/posts-by-ids'
    )]
    public function postsByIds(
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
            path: '/posts/' . $this->client->segment($ids),
            backoffMethod: 'GET /posts/{ids}',
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
     * Write operation. Renders a hypothetical comment on the given post.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Write operation. Renders a hypothetical comment on the given post. https://api.stackexchange.com/docs/render-comment'
    )]
    public function renderComment(
        int $id,
        ?string $site = null,
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'body' => ['type' => 'string'],
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'POST',
            path: '/posts/' . $this->client->segment((string) $id) . '/comments/render',
            backoffMethod: 'POST /posts/{id}/comments/render',
            parameters: $parameters,
            site: $site ?? '',
            write: true
        );
    }

    /**
     * Get revisions on the set of posts in ids.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get revisions on the set of posts in ids. https://api.stackexchange.com/docs/revisions-by-ids'
    )]
    public function revisionsByIds(
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
            path: '/posts/' . $this->client->segment($ids) . '/revisions',
            backoffMethod: 'GET /posts/{ids}/revisions',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get suggested edits on the set of posts in ids.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get suggested edits on the set of posts in ids. https://api.stackexchange.com/docs/posts-on-suggested-edits'
    )]
    public function postsOnSuggestedEdits(
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
                    'sort' => ['type' => 'string', 'enum' => ['creation', 'approval', 'rejection']],
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'GET',
            path: '/posts/' . $this->client->segment($ids) . '/suggested-edits',
            backoffMethod: 'GET /posts/{ids}/suggested-edits',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get all the privileges available on the site.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get all the privileges available on the site. https://api.stackexchange.com/docs/privileges'
    )]
    public function privileges(
        ?string $site = null,
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
            path: '/privileges',
            backoffMethod: 'GET /privileges',
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
     * Write operation. Renders a hypothetical answer to a question.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Write operation. Renders a hypothetical answer to a question. https://api.stackexchange.com/docs/render-answer'
    )]
    public function renderAnswer(
        int $id,
        ?string $site = null,
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'body' => ['type' => 'string'],
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'POST',
            path: '/questions/' . $this->client->segment((string) $id) . '/answers/render',
            backoffMethod: 'POST /questions/{id}/answers/render',
            parameters: $parameters,
            site: $site ?? '',
            write: true
        );
    }

    /**
     * Returns valid flag options which are also close reasons for the given question.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Returns valid flag options which are also close reasons for the given question. https://api.stackexchange.com/docs/question-close-options'
    )]
    public function questionCloseOptions(
        int $id,
        ?string $site = null,
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'GET',
            path: '/questions/' . $this->client->segment((string) $id) . '/close/options',
            backoffMethod: 'GET /questions/{id}/close/options',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true
        );
    }

    /**
     * Get the comments on the questions identified by a set of ids.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get the comments on the questions identified by a set of ids. https://api.stackexchange.com/docs/comments-on-questions'
    )]
    public function commentsOnQuestions(
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
            path: '/questions/' . $this->client->segment($ids) . '/comments',
            backoffMethod: 'GET /questions/{ids}/comments',
            parameters: $parameters,
            site: $site ?? ''
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
     * Write operation. Casts a downvote on the given question.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Write operation. Casts a downvote on the given question. https://api.stackexchange.com/docs/downvote-question'
    )]
    public function downvoteQuestion(
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
            path: '/questions/' . $this->client->segment((string) $id) . '/downvote',
            backoffMethod: 'POST /questions/{id}/downvote',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true,
            write: true
        );
    }

    /**
     * Write operation. Undoes a downvote on the given question.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Write operation. Undoes a downvote on the given question. https://api.stackexchange.com/docs/undo-downvote-question'
    )]
    public function undoDownvoteQuestion(
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
            path: '/questions/' . $this->client->segment((string) $id) . '/downvote/undo',
            backoffMethod: 'POST /questions/{id}/downvote/undo',
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
     * Write operation. Bookmarks the given question. (Previously known as "favoriting" a question).
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Write operation. Bookmarks the given question. (Previously known as "favoriting" a question). https://api.stackexchange.com/docs/favorite-question'
    )]
    public function favoriteQuestion(
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
            path: '/questions/' . $this->client->segment((string) $id) . '/favorite',
            backoffMethod: 'POST /questions/{id}/favorite',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true,
            write: true
        );
    }

    /**
     * Write operation. Undoes bookmarking the given question. (Previously known as "favoriting" a question).
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Write operation. Undoes bookmarking the given question. (Previously known as "favoriting" a question). https://api.stackexchange.com/docs/undo-favorite-question'
    )]
    public function undoFavoriteQuestion(
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
            path: '/question/' . $this->client->segment((string) $id) . '/favorite/undo',
            backoffMethod: 'POST /questions/{id}/favorite/undo',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true,
            write: true
        );
    }

    /**
     * Write operation. Casts a flag on the given question.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Write operation. Casts a flag on the given question. https://api.stackexchange.com/docs/create-question-flag'
    )]
    public function createQuestionFlag(
        int $id,
        ?string $site = null,
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'option_id' => ['type' => 'integer'],
                    'comment' => ['type' => 'string'],
                    'target_site' => ['type' => 'string'],
                    'question_id' => ['type' => 'integer'],
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
            path: '/questions/' . $this->client->segment((string) $id) . '/flags/add',
            backoffMethod: 'POST /questions/{id}/flags/add',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true,
            write: true
        );
    }

    /**
     * Returns valid flag options for the given question.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Returns valid flag options for the given question. https://api.stackexchange.com/docs/question-flag-options'
    )]
    public function questionFlagOptions(
        int $id,
        ?string $site = null,
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'GET',
            path: '/questions/' . $this->client->segment((string) $id) . '/flags/options',
            backoffMethod: 'GET /questions/{id}/flags/options',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true
        );
    }

    /**
     * Get the questions that link to the questions identified by a set of ids.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get the questions that link to the questions identified by a set of ids. https://api.stackexchange.com/docs/linked-questions'
    )]
    public function linkedQuestions(
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
                    'sort' => ['type' => 'string', 'enum' => ['activity', 'votes', 'creation', 'rank']],
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'GET',
            path: '/questions/' . $this->client->segment($ids) . '/linked',
            backoffMethod: 'GET /questions/{ids}/linked',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get the questions that are related to the questions identified by a set of ids.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get the questions that are related to the questions identified by a set of ids. https://api.stackexchange.com/docs/related-questions'
    )]
    public function relatedQuestions(
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
                    'sort' => ['type' => 'string', 'enum' => ['activity', 'votes', 'creation', 'rank']],
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'GET',
            path: '/questions/' . $this->client->segment($ids) . '/related',
            backoffMethod: 'GET /questions/{ids}/related',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Write operation. Creates a suggested_edit on an existing question.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Write operation. Creates a suggested_edit on an existing question. https://api.stackexchange.com/docs/create-question-suggested-edit'
    )]
    public function createQuestionSuggestedEdit(
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
            path: '/questions/' . $this->client->segment((string) $id) . '/suggested-edit/add',
            backoffMethod: 'POST /questions/{id}/suggested-edit/add',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true,
            write: true
        );
    }

    /**
     * Get the timelines of the questions identified by a set of ids.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get the timelines of the questions identified by a set of ids. https://api.stackexchange.com/docs/questions-timeline'
    )]
    public function questionsTimeline(
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
            path: '/questions/' . $this->client->segment($ids) . '/timeline',
            backoffMethod: 'GET /questions/{ids}/timeline',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Write operation. Casts an upvote on the given question.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Write operation. Casts an upvote on the given question. https://api.stackexchange.com/docs/upvote-question'
    )]
    public function upvoteQuestion(
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
            path: '/questions/' . $this->client->segment((string) $id) . '/upvote',
            backoffMethod: 'POST /questions/{id}/upvote',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true,
            write: true
        );
    }

    /**
     * Write operation. Undoes an upvote on the given question.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Write operation. Undoes an upvote on the given question. https://api.stackexchange.com/docs/undo-upvote-question'
    )]
    public function undoUpvoteQuestion(
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
            path: '/question/' . $this->client->segment((string) $id) . '/upvote/undo',
            backoffMethod: 'POST /questions/{id}/upvote/undo',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true,
            write: true
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
     * Get all questions on the site with active bounties.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get all questions on the site with active bounties. https://api.stackexchange.com/docs/featured-questions'
    )]
    public function featuredQuestions(
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
            path: '/questions/featured',
            backoffMethod: 'GET /questions/featured',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get all questions on the site with no answers.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get all questions on the site with no answers. https://api.stackexchange.com/docs/no-answer-questions'
    )]
    public function noAnswerQuestions(
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
            path: '/questions/no-answers',
            backoffMethod: 'GET /questions/no-answers',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Write operation. Renders a hypothetical question.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Write operation. Renders a hypothetical question. https://api.stackexchange.com/docs/render-question'
    )]
    public function renderQuestion(
        ?string $site = null,
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'title' => ['type' => 'string'],
                    'body' => ['type' => 'string'],
                    'tags' => ['type' => 'array', 'items' => ['type' => 'string']],
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'POST',
            path: '/questions/render',
            backoffMethod: 'POST /questions/render',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true,
            write: true
        );
    }

    /**
     * Get all questions the site considers unanswered.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get all questions the site considers unanswered. https://api.stackexchange.com/docs/unanswered-questions'
    )]
    public function unansweredQuestions(
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
            path: '/questions/unanswered',
            backoffMethod: 'GET /questions/unanswered',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get questions the site considers unanswered within a user's favorite or interesting tags.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get questions the site considers unanswered within a user\'s favorite or interesting tags. https://api.stackexchange.com/docs/unanswered-questions-my-tags'
    )]
    public function unansweredQuestionsMyTags(
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
            path: '/questions/unanswered/my-tags',
            backoffMethod: 'GET /questions/unanswered/my-tags',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true
        );
    }

    /**
     * Get all revisions identified by a set of ids.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get all revisions identified by a set of ids. https://api.stackexchange.com/docs/revisions-by-guids'
    )]
    public function revisionsByGuids(
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
            path: '/revisions/' . $this->client->segment($ids),
            backoffMethod: 'GET /revisions/{ids}',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Search the site for questions meeting certain criteria.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Search the site for questions meeting certain criteria. https://api.stackexchange.com/docs/search'
    )]
    public function search(
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
                    'tagged' => ['type' => 'array', 'items' => ['type' => 'string']],
                    'nottagged' => ['type' => 'array', 'items' => ['type' => 'string']],
                    'intitle' => ['type' => 'string'],
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'GET',
            path: '/search',
            backoffMethod: 'GET /search',
            parameters: $parameters,
            site: $site ?? ''
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
     * Search the site based on similarity to a title.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Search the site based on similarity to a title. https://api.stackexchange.com/docs/similar'
    )]
    public function similar(
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
                    'tagged' => ['type' => 'array', 'items' => ['type' => 'string']],
                    'nottagged' => ['type' => 'array', 'items' => ['type' => 'string']],
                    'title' => ['type' => 'string'],
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'GET',
            path: '/similar',
            backoffMethod: 'GET /similar',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Searches a site.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(description: 'Searches a site. https://api.stackexchange.com/docs/excerpt-search')]
    public function excerptSearch(
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
            path: '/search/excerpts',
            backoffMethod: 'GET /search/excerpts',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get all the suggested edits on the site.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get all the suggested edits on the site. https://api.stackexchange.com/docs/suggested-edits'
    )]
    public function suggestedEdits(
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
                    'sort' => ['type' => 'string', 'enum' => ['creation', 'approval', 'rejection']],
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'GET',
            path: '/suggested-edits',
            backoffMethod: 'GET /suggested-edits',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get the suggested edits identified by a set of ids.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get the suggested edits identified by a set of ids. https://api.stackexchange.com/docs/suggested-edits-by-ids'
    )]
    public function suggestedEditsByIds(
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
                    'sort' => ['type' => 'string', 'enum' => ['creation', 'approval', 'rejection']],
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'GET',
            path: '/suggested-edits/' . $this->client->segment($ids),
            backoffMethod: 'GET /suggested-edits/{ids}',
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
     * Get tags on the site by their names.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(description: 'Get tags on the site by their names. https://api.stackexchange.com/docs/tags-by-name')]
    public function tagsByName(
        string $tags,
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
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'GET',
            path: '/tags/' . $this->client->segment($tags) . '/info',
            backoffMethod: 'GET /tags/{tags}/info',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get the tags on the site that only moderators can use.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get the tags on the site that only moderators can use. https://api.stackexchange.com/docs/moderator-only-tags'
    )]
    public function moderatorOnlyTags(
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
            path: '/tags/moderator-only',
            backoffMethod: 'GET /tags/moderator-only',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get the tags on the site that fulfill required tag constraints.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get the tags on the site that fulfill required tag constraints. https://api.stackexchange.com/docs/required-tags'
    )]
    public function requiredTags(
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
            path: '/tags/required',
            backoffMethod: 'GET /tags/required',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get all the tag synonyms on the site.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(description: 'Get all the tag synonyms on the site. https://api.stackexchange.com/docs/tag-synonyms')]
    public function tagSynonyms(
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
                    'sort' => ['type' => 'string', 'enum' => ['creation', 'applied', 'activity']],
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'GET',
            path: '/tags/synonyms',
            backoffMethod: 'GET /tags/synonyms',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get frequently asked questions in a set of tags.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get frequently asked questions in a set of tags. https://api.stackexchange.com/docs/faqs-by-tags'
    )]
    public function faqsByTags(
        string $tags,
        ?string $site = null,
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
            path: '/tags/' . $this->client->segment($tags) . '/faq',
            backoffMethod: 'GET /tags/{tags}/faq',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get related tags, based on common tag pairings.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get related tags, based on common tag pairings. https://api.stackexchange.com/docs/related-tags'
    )]
    public function relatedTags(
        string $tags,
        ?string $site = null,
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
            path: '/tags/' . $this->client->segment($tags) . '/related',
            backoffMethod: 'GET /tags/{tags}/related',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get the synonyms for a specific set of tags.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get the synonyms for a specific set of tags. https://api.stackexchange.com/docs/synonyms-by-tags'
    )]
    public function synonymsByTags(
        string $tags,
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
                    'sort' => ['type' => 'string', 'enum' => ['creation', 'applied', 'activity']],
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'GET',
            path: '/tags/' . $this->client->segment($tags) . '/synonyms',
            backoffMethod: 'GET /tags/{tags}/synonyms',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get the top answer posters in a specific tag, either in the last month or for all time.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get the top answer posters in a specific tag, either in the last month or for all time. https://api.stackexchange.com/docs/top-answerers-on-tags'
    )]
    public function topAnswerersOnTags(
        string $tag,
        string $period,
        ?string $site = null,
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
            path: '/tags/' . $this->client->segment($tag) . '/top-answerers/' . $this->client->segment($period),
            backoffMethod: 'GET /tags/{tag}/top-answerers/{period}',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get the top question askers in a specific tag, either in the last month or for all time.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get the top question askers in a specific tag, either in the last month or for all time. https://api.stackexchange.com/docs/top-askers-on-tags'
    )]
    public function topAskersOnTags(
        string $tag,
        string $period,
        ?string $site = null,
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
            path: '/tags/' . $this->client->segment($tag) . '/top-askers/' . $this->client->segment($period),
            backoffMethod: 'GET /tags/{tag}/top-askers/{period}',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get the wiki entries for a set of tags.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(description: 'Get the wiki entries for a set of tags. https://api.stackexchange.com/docs/wikis-by-tags')]
    public function wikisByTags(
        string $tags,
        ?string $site = null,
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
            path: '/tags/' . $this->client->segment($tags) . '/wikis',
            backoffMethod: 'GET /tags/{tags}/wikis',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get all users on the site.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(description: 'Get all users on the site. https://api.stackexchange.com/docs/users')]
    public function users(
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
            path: '/users',
            backoffMethod: 'GET /users',
            parameters: $parameters,
            site: $site ?? ''
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
     * Get the answers posted by the users identified by a set of ids.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get the answers posted by the users identified by a set of ids. https://api.stackexchange.com/docs/answers-on-users'
    )]
    public function answersOnUsers(
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
            path: '/users/' . $this->client->segment($ids) . '/answers',
            backoffMethod: 'GET /users/{ids}/answers',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get the answers posted by the users identified by a set of ids.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get the answers posted by the users identified by a set of ids. https://api.stackexchange.com/docs/me-answers'
    )]
    public function meAnswers(
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
            path: '/me/answers',
            backoffMethod: 'GET /users/{ids}/answers',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true
        );
    }

    /**
     * Get the badges earned by the users identified by a set of ids.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get the badges earned by the users identified by a set of ids. https://api.stackexchange.com/docs/badges-on-users'
    )]
    public function badgesOnUsers(
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
                    'sort' => ['type' => 'string', 'enum' => ['rank', 'name', 'type', 'awarded']],
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'GET',
            path: '/users/' . $this->client->segment($ids) . '/badges',
            backoffMethod: 'GET /users/{ids}/badges',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get the badges earned by the users identified by a set of ids.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get the badges earned by the users identified by a set of ids. https://api.stackexchange.com/docs/me-badges'
    )]
    public function meBadges(
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
                    'sort' => ['type' => 'string', 'enum' => ['rank', 'name', 'type']],
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'GET',
            path: '/me/badges',
            backoffMethod: 'GET /users/{ids}/badges',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true
        );
    }

    /**
     * Get the comments posted by the users identified by a set of ids.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get the comments posted by the users identified by a set of ids. https://api.stackexchange.com/docs/comments-on-users'
    )]
    public function commentsOnUsers(
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
            path: '/users/' . $this->client->segment($ids) . '/comments',
            backoffMethod: 'GET /users/{ids}/comments',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get the comments posted by the users identified by a set of ids.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get the comments posted by the users identified by a set of ids. https://api.stackexchange.com/docs/me-comments'
    )]
    public function meComments(
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
            path: '/me/comments',
            backoffMethod: 'GET /users/{ids}/comments',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true
        );
    }

    /**
     * Get the comments posted by a set of users in reply to another user.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get the comments posted by a set of users in reply to another user. https://api.stackexchange.com/docs/comments-by-users-to-user'
    )]
    public function commentsByUsersToUser(
        string $ids,
        int $toid,
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
            path: '/users/' . $this->client->segment($ids) . '/comments/' . $this->client->segment((string) $toid),
            backoffMethod: 'GET /users/{ids}/comments/{toid}',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get the comments posted by a set of users in reply to another user.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get the comments posted by a set of users in reply to another user. https://api.stackexchange.com/docs/me-comments-to'
    )]
    public function meCommentsTo(
        int $toId,
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
            path: '/me/comments/' . $this->client->segment((string) $toId),
            backoffMethod: 'GET /users/{ids}/comments/{toid}',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true
        );
    }

    /**
     * Get the questions bookmarked (previously known as "favorited") by users identified by a set of ids.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get the questions bookmarked (previously known as "favorited") by users identified by a set of ids. https://api.stackexchange.com/docs/favorites-on-users'
    )]
    public function favoritesOnUsers(
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
                    'sort' => ['type' => 'string', 'enum' => ['activity', 'votes', 'creation', 'added']],
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'GET',
            path: '/users/' . $this->client->segment($ids) . '/favorites',
            backoffMethod: 'GET /users/{ids}/favorites',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get the questions bookmarked (previously known as "favorited") by users identified by a set of ids.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get the questions bookmarked (previously known as "favorited") by users identified by a set of ids. https://api.stackexchange.com/docs/me-favorites'
    )]
    public function meFavorites(
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
                    'sort' => ['type' => 'string', 'enum' => ['activity', 'votes', 'creation', 'added']],
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'GET',
            path: '/me/favorites',
            backoffMethod: 'GET /users/{ids}/favorites',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true
        );
    }

    /**
     * Get the comments that mention one of the users identified by a set of ids.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get the comments that mention one of the users identified by a set of ids. https://api.stackexchange.com/docs/mentions-on-users'
    )]
    public function mentionsOnUsers(
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
            path: '/users/' . $this->client->segment($ids) . '/mentioned',
            backoffMethod: 'GET /users/{ids}/mentioned',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get the comments that mention one of the users identified by a set of ids.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get the comments that mention one of the users identified by a set of ids. https://api.stackexchange.com/docs/me-mentioned'
    )]
    public function meMentioned(
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
            path: '/me/mentioned',
            backoffMethod: 'GET /users/{ids}/mentioned',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true
        );
    }

    /**
     * Gets a user's activity across the Stack Exchange network.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Gets a user\'s activity across the Stack Exchange network. https://api.stackexchange.com/docs/users-network-activity'
    )]
    public function usersNetworkActivity(
        int $id,
        ?string $site = null,
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'page' => ['type' => 'integer'],
                    'pagesize' => ['type' => 'integer'],
                    'fromdate' => ['type' => 'integer'],
                    'todate' => ['type' => 'integer'],
                    'types' => ['type' => 'string'],
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'GET',
            path: '/users/' . $this->client->segment((string) $id) . '/network-activity',
            backoffMethod: 'GET /users/{id}/network-activity',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Gets a user's activity across the Stack Exchange network.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Gets a user\'s activity across the Stack Exchange network. https://api.stackexchange.com/docs/me-network-activity'
    )]
    public function meNetworkActivity(
        ?string $site = null,
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'page' => ['type' => 'integer'],
                    'pagesize' => ['type' => 'integer'],
                    'types' => ['type' => 'string'],
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'GET',
            path: '/me/network-activity',
            backoffMethod: 'GET /users/{id}/network-activity',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true
        );
    }

    /**
     * Get a user's notifications.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(description: 'Get a user\'s notifications. https://api.stackexchange.com/docs/user-notifications')]
    public function userNotifications(
        int $id,
        ?string $site = null,
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
            path: '/users/' . $this->client->segment((string) $id) . '/notifications',
            backoffMethod: 'GET /users/{id}/notifications',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true
        );
    }

    /**
     * Get a user's notifications.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(description: 'Get a user\'s notifications. https://api.stackexchange.com/docs/me-notifications')]
    public function meNotifications(
        ?string $site = null,
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
            path: '/me/notifications',
            backoffMethod: 'GET /users/{id}/notifications',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true
        );
    }

    /**
     * Get a user's unread notifications.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get a user\'s unread notifications. https://api.stackexchange.com/docs/user-unread-notifications'
    )]
    public function userUnreadNotifications(
        int $id,
        ?string $site = null,
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
            path: '/users/' . $this->client->segment((string) $id) . '/notifications/unread',
            backoffMethod: 'GET /users/{id}/notifications/unread',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true
        );
    }

    /**
     * Get a user's unread notifications.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get a user\'s unread notifications. https://api.stackexchange.com/docs/me-unread-notifications'
    )]
    public function meUnreadNotifications(
        ?string $site = null,
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
            path: '/me/notifications/unread',
            backoffMethod: 'GET /users/{id}/notifications/unread',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true
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
     * Get all posts (questions and answers) owned by a set of users.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get all posts (questions and answers) owned by a set of users. https://api.stackexchange.com/docs/me-posts'
    )]
    public function mePosts(
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
            path: '/me/posts',
            backoffMethod: 'GET /users/{ids}/posts',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true
        );
    }

    /**
     * Get the privileges the given user has on the site.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get the privileges the given user has on the site. https://api.stackexchange.com/docs/privileges-on-users'
    )]
    public function privilegesOnUsers(
        int $id,
        ?string $site = null,
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
            path: '/users/' . $this->client->segment((string) $id) . '/privileges',
            backoffMethod: 'GET /users/{id}/privileges',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get the privileges the given user has on the site.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get the privileges the given user has on the site. https://api.stackexchange.com/docs/me-privileges'
    )]
    public function mePrivileges(
        ?string $site = null,
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
            path: '/me/privileges',
            backoffMethod: 'GET /users/{id}/privileges',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true
        );
    }

    /**
     * Get the questions asked by the users identified by a set of ids.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get the questions asked by the users identified by a set of ids. https://api.stackexchange.com/docs/questions-on-users'
    )]
    public function questionsOnUsers(
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
            path: '/users/' . $this->client->segment($ids) . '/questions',
            backoffMethod: 'GET /users/{ids}/questions',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get the questions asked by the users identified by a set of ids.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get the questions asked by the users identified by a set of ids. https://api.stackexchange.com/docs/me-questions'
    )]
    public function meQuestions(
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
            path: '/me/questions',
            backoffMethod: 'GET /users/{ids}/questions',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true
        );
    }

    /**
     * Get the questions on which a set of users, have active bounties.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get the questions on which a set of users, have active bounties. https://api.stackexchange.com/docs/featured-questions-on-users'
    )]
    public function featuredQuestionsOnUsers(
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
            path: '/users/' . $this->client->segment($ids) . '/questions/featured',
            backoffMethod: 'GET /users/{ids}/questions/featured',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get the questions on which a set of users, have active bounties.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get the questions on which a set of users, have active bounties. https://api.stackexchange.com/docs/me-featured-questions'
    )]
    public function meFeaturedQuestions(
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
            path: '/me/questions/featured',
            backoffMethod: 'GET /users/{ids}/questions/featured',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true
        );
    }

    /**
     * Get the questions asked by a set of users, which have no answers.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get the questions asked by a set of users, which have no answers. https://api.stackexchange.com/docs/no-answer-questions-on-users'
    )]
    public function noAnswerQuestionsOnUsers(
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
            path: '/users/' . $this->client->segment($ids) . '/questions/no-answers',
            backoffMethod: 'GET /users/{ids}/questions/no-answers',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get the questions asked by a set of users, which have no answers.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get the questions asked by a set of users, which have no answers. https://api.stackexchange.com/docs/me-no-answer-questions'
    )]
    public function meNoAnswerQuestions(
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
            path: '/me/questions/no-answers',
            backoffMethod: 'GET /users/{ids}/questions/no-answers',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true
        );
    }

    /**
     * Get the questions asked by a set of users, which have at least one answer but no accepted answer.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get the questions asked by a set of users, which have at least one answer but no accepted answer. https://api.stackexchange.com/docs/unaccepted-questions-on-users'
    )]
    public function unacceptedQuestionsOnUsers(
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
            path: '/users/' . $this->client->segment($ids) . '/questions/unaccepted',
            backoffMethod: 'GET /users/{ids}/questions/unaccepted',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get the questions asked by a set of users, which have at least one answer but no accepted answer.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get the questions asked by a set of users, which have at least one answer but no accepted answer. https://api.stackexchange.com/docs/me-unaccepted-questions'
    )]
    public function meUnacceptedQuestions(
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
            path: '/me/questions/unaccepted',
            backoffMethod: 'GET /users/{ids}/questions/unaccepted',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true
        );
    }

    /**
     * Get the questions asked by a set of users, which are not considered to be adequately answered.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get the questions asked by a set of users, which are not considered to be adequately answered. https://api.stackexchange.com/docs/unanswered-questions-on-users'
    )]
    public function unansweredQuestionsOnUsers(
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
            path: '/users/' . $this->client->segment($ids) . '/questions/unanswered',
            backoffMethod: 'GET /users/{ids}/questions/unanswered',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get the questions asked by a set of users, which are not considered to be adequately answered.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get the questions asked by a set of users, which are not considered to be adequately answered. https://api.stackexchange.com/docs/me-unanswered-questions'
    )]
    public function meUnansweredQuestions(
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
            path: '/me/questions/unanswered',
            backoffMethod: 'GET /users/{ids}/questions/unanswered',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true
        );
    }

    /**
     * Get a subset of the reputation changes experienced by the users identified by a set of ids.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get a subset of the reputation changes experienced by the users identified by a set of ids. https://api.stackexchange.com/docs/reputation-on-users'
    )]
    public function reputationOnUsers(
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
            path: '/users/' . $this->client->segment($ids) . '/reputation',
            backoffMethod: 'GET /users/{ids}/reputation',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get a subset of the reputation changes experienced by the users identified by a set of ids.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get a subset of the reputation changes experienced by the users identified by a set of ids. https://api.stackexchange.com/docs/me-reputation'
    )]
    public function meReputation(
        ?string $site = null,
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'GET',
            path: '/me/reputation',
            backoffMethod: 'GET /users/{ids}/reputation',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true
        );
    }

    /**
     * Get a history of a user's reputation, excluding private events.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get a history of a user\'s reputation, excluding private events. https://api.stackexchange.com/docs/reputation-history'
    )]
    public function reputationHistory(
        string $ids,
        ?string $site = null,
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
            path: '/users/' . $this->client->segment($ids) . '/reputation-history',
            backoffMethod: 'GET /users/{ids}/reputation-history',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get a history of a user's reputation, excluding private events.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get a history of a user\'s reputation, excluding private events. https://api.stackexchange.com/docs/me-reputation-history'
    )]
    public function meReputationHistory(
        ?string $site = null,
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
            path: '/me/reputation-history',
            backoffMethod: 'GET /users/{ids}/reputation-history',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true
        );
    }

    /**
     * Get a full history of a user's reputation.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get a full history of a user\'s reputation. https://api.stackexchange.com/docs/full-reputation-history'
    )]
    public function fullReputationHistory(
        int $id,
        ?string $site = null,
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
            path: '/users/' . $this->client->segment((string) $id) . '/reputation-history/full',
            backoffMethod: 'GET /users/{id}/reputation-history/full',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true
        );
    }

    /**
     * Get a full history of a user's reputation.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get a full history of a user\'s reputation. https://api.stackexchange.com/docs/me-full-reputation-history'
    )]
    public function meFullReputationHistory(
        ?string $site = null,
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
            path: '/me/reputation-history/full',
            backoffMethod: 'GET /users/{id}/reputation-history/full',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true
        );
    }

    /**
     * Get the suggested edits provided by users identified by a set of ids.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get the suggested edits provided by users identified by a set of ids. https://api.stackexchange.com/docs/suggested-edits-on-users'
    )]
    public function suggestedEditsOnUsers(
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
                    'sort' => ['type' => 'string', 'enum' => ['creation', 'approval', 'rejection']],
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'GET',
            path: '/users/' . $this->client->segment($ids) . '/suggested-edits',
            backoffMethod: 'GET /users/{ids}/suggested-edits',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get the suggested edits provided by users identified by a set of ids.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get the suggested edits provided by users identified by a set of ids. https://api.stackexchange.com/docs/me-suggested-edits'
    )]
    public function meSuggestedEdits(
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
                    'sort' => ['type' => 'string', 'enum' => ['creation', 'approval', 'rejection']],
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'GET',
            path: '/me/suggested-edits',
            backoffMethod: 'GET /users/{ids}/suggested-edits',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true
        );
    }

    /**
     * Get the tags that the users (identified by a set of ids) have been active in.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get the tags that the users (identified by a set of ids) have been active in. https://api.stackexchange.com/docs/tags-on-users'
    )]
    public function tagsOnUsers(
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
                    'sort' => ['type' => 'string', 'enum' => ['popular', 'activity', 'name']],
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'GET',
            path: '/users/' . $this->client->segment($ids) . '/tags',
            backoffMethod: 'GET /users/{ids}/tags',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get the tags that the users (identified by a set of ids) have been active in.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get the tags that the users (identified by a set of ids) have been active in. https://api.stackexchange.com/docs/me-tags'
    )]
    public function meTags(
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
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'GET',
            path: '/me/tags',
            backoffMethod: 'GET /users/{ids}/tags',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true
        );
    }

    /**
     * Get the top answers a user has posted on questions with a set of tags.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get the top answers a user has posted on questions with a set of tags. https://api.stackexchange.com/docs/top-user-answers-in-tags'
    )]
    public function topUserAnswersInTags(
        int $id,
        string $tags,
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
            path: '/users/' .
                $this->client->segment((string) $id) .
                '/tags/' .
                $this->client->segment($tags) .
                '/top-answers',
            backoffMethod: 'GET /users/{id}/tags/{tags}/top-answers',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get the top answers a user has posted on questions with a set of tags.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get the top answers a user has posted on questions with a set of tags. https://api.stackexchange.com/docs/me-tags-top-answers'
    )]
    public function meTagsTopAnswers(
        string $tags,
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
            path: '/me/tags/' . $this->client->segment($tags) . '/top-answers',
            backoffMethod: 'GET /users/{id}/tags/{tags}/top-answers',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true
        );
    }

    /**
     * Get the top questions a user has posted with a set of tags.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get the top questions a user has posted with a set of tags. https://api.stackexchange.com/docs/top-user-questions-in-tags'
    )]
    public function topUserQuestionsInTags(
        int $id,
        string $tags,
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
            path: '/users/' .
                $this->client->segment((string) $id) .
                '/tags/' .
                $this->client->segment($tags) .
                '/top-questions',
            backoffMethod: 'GET /users/{id}/tags/{tags}/top-questions',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get the top questions a user has posted with a set of tags.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get the top questions a user has posted with a set of tags. https://api.stackexchange.com/docs/me-tags-top-questions'
    )]
    public function meTagsTopQuestions(
        string $tags,
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
                    'sort' => [
                        'type' => 'string',
                        'enum' => ['activity', 'votes', 'creation', 'hot', 'week', 'month', 'relevance']
                    ],
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'GET',
            path: '/me/tags/' . $this->client->segment($tags) . '/top-questions',
            backoffMethod: 'GET /users/{id}/tags/{tags}/top-questions',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true
        );
    }

    /**
     * Get a given user's tag preferences.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(description: 'Get a given user\'s tag preferences. https://api.stackexchange.com/docs/tag-prefs-on-user')]
    public function tagPrefsOnUser(
        int $id,
        ?string $site = null,
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
            path: '/users/' . $this->client->segment((string) $id) . '/tag-preferences',
            backoffMethod: 'GET /users/{id}/tag-preferences',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true
        );
    }

    /**
     * Get a given user's tag preferences.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(description: 'Get a given user\'s tag preferences. https://api.stackexchange.com/docs/me-tag-prefs')]
    public function meTagPrefs(
        ?string $site = null,
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'page' => ['type' => 'integer'],
                    'pagesize' => ['type' => 'integer'],
                    'id' => ['type' => 'integer'],
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'GET',
            path: '/me/tag-preferences',
            backoffMethod: 'GET /users/{id}/tag-preferences',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true
        );
    }

    /**
     * Write operation. Edit a user's tag preferences.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Write operation. Edit a user\'s tag preferences. https://api.stackexchange.com/docs/edit-tag-prefs-on-user'
    )]
    public function editTagPrefsOnUser(
        int $id,
        ?string $site = null,
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'favorite_tags' => ['type' => 'array', 'items' => ['type' => 'string']],
                    'ignored_tags' => ['type' => 'array', 'items' => ['type' => 'string']],
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
            path: '/users/' . $this->client->segment((string) $id) . '/tag-preferences/edit',
            backoffMethod: 'POST /users/{id}/tag-preferences/edit',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true,
            write: true
        );
    }

    /**
     * Write operation. Edit a user's tag preferences.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Write operation. Edit a user\'s tag preferences. https://api.stackexchange.com/docs/me-edit-tag-prefs'
    )]
    public function meEditTagPrefs(
        ?string $site = null,
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'favorite_tags' => ['type' => 'array', 'items' => ['type' => 'string']],
                    'ignored_tags' => ['type' => 'array', 'items' => ['type' => 'string']],
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
            path: '/me/tag-preferences/edit',
            backoffMethod: 'POST /users/{id}/tag-preferences/edit',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true,
            write: true
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

    /**
     * Get the top tags (by score) a single user has posted answers in.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get the top tags (by score) a single user has posted answers in. https://api.stackexchange.com/docs/top-answer-tags-on-users'
    )]
    public function topAnswerTagsOnUsers(
        int $id,
        ?string $site = null,
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
            path: '/users/' . $this->client->segment((string) $id) . '/top-answer-tags',
            backoffMethod: 'GET /users/{id}/top-answer-tags',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get the top tags (by score) a single user has posted answers in.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get the top tags (by score) a single user has posted answers in. https://api.stackexchange.com/docs/me-top-answer-tags'
    )]
    public function meTopAnswerTags(
        ?string $site = null,
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
            path: '/me/top-answer-tags',
            backoffMethod: 'GET /users/{id}/top-answer-tags',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true
        );
    }

    /**
     * Get the top tags (by score) a single user has asked questions in.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get the top tags (by score) a single user has asked questions in. https://api.stackexchange.com/docs/top-question-tags-on-users'
    )]
    public function topQuestionTagsOnUsers(
        int $id,
        ?string $site = null,
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
            path: '/users/' . $this->client->segment((string) $id) . '/top-question-tags',
            backoffMethod: 'GET /users/{id}/top-question-tags',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get the top tags (by score) a single user has asked questions in.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get the top tags (by score) a single user has asked questions in. https://api.stackexchange.com/docs/me-top-question-tags'
    )]
    public function meTopQuestionTags(
        ?string $site = null,
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
            path: '/me/top-question-tags',
            backoffMethod: 'GET /users/{id}/top-question-tags',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true
        );
    }

    /**
     * Get the top tags (by score) a single user has posted in.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get the top tags (by score) a single user has posted in. https://api.stackexchange.com/docs/top-tags-on-users'
    )]
    public function topTagsOnUsers(
        int $id,
        ?string $site = null,
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
            path: '/users/' . $this->client->segment((string) $id) . '/top-tags',
            backoffMethod: 'GET /users/{id}/top-tags',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get the top tags (by score) a single user has posted in.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get the top tags (by score) a single user has posted in. https://api.stackexchange.com/docs/me-top-tags'
    )]
    public function meTopTags(
        ?string $site = null,
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
            path: '/me/top-tags',
            backoffMethod: 'GET /users/{id}/top-tags',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true
        );
    }

    /**
     * Get the users who have moderation powers on the site.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get the users who have moderation powers on the site. https://api.stackexchange.com/docs/moderators'
    )]
    public function moderators(
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
            path: '/users/moderators',
            backoffMethod: 'GET /users/moderators',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get the users who are active moderators who have also won a moderator election.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get the users who are active moderators who have also won a moderator election. https://api.stackexchange.com/docs/elected-moderators'
    )]
    public function electedModerators(
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
            path: '/users/moderators/elected',
            backoffMethod: 'GET /users/moderators/elected',
            parameters: $parameters,
            site: $site ?? ''
        );
    }

    /**
     * Get a user's inbox.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(description: 'Get a user\'s inbox. https://api.stackexchange.com/docs/user-inbox')]
    public function userInbox(
        int $id,
        ?string $site = null,
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
            path: '/users/' . $this->client->segment((string) $id) . '/inbox',
            backoffMethod: 'GET /users/{id}/inbox',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true
        );
    }

    /**
     * Get a user's inbox.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(description: 'Get a user\'s inbox. https://api.stackexchange.com/docs/me-inbox')]
    public function meInbox(
        ?string $site = null,
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
            path: '/me/inbox',
            backoffMethod: 'GET /users/{id}/inbox',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true
        );
    }

    /**
     * Get the unread items in a user's inbox.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get the unread items in a user\'s inbox. https://api.stackexchange.com/docs/user-unread-inbox'
    )]
    public function userUnreadInbox(
        int $id,
        ?string $site = null,
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'page' => ['type' => 'integer'],
                    'pagesize' => ['type' => 'integer'],
                    'since' => ['type' => 'integer'],
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'GET',
            path: '/users/' . $this->client->segment((string) $id) . '/inbox/unread',
            backoffMethod: 'GET /users/{id}/inbox/unread',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true
        );
    }

    /**
     * Get the unread items in a user's inbox.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get the unread items in a user\'s inbox. https://api.stackexchange.com/docs/me-unread-inbox'
    )]
    public function meUnreadInbox(
        ?string $site = null,
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'page' => ['type' => 'integer'],
                    'pagesize' => ['type' => 'integer'],
                    'since' => ['type' => 'integer'],
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'GET',
            path: '/me/inbox/unread',
            backoffMethod: 'GET /users/{id}/inbox/unread',
            parameters: $parameters,
            site: $site ?? '',
            authenticated: true
        );
    }

    /**
     * Write operation. Allows an application to dispose of access_tokens when it is done with them.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Write operation. Allows an application to dispose of access_tokens when it is done with them. https://api.stackexchange.com/docs/invalidate-access-tokens'
    )]
    public function invalidateAccessTokens(
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
            path: '/access-tokens/' . $this->client->encodedAccessToken() . '/invalidate',
            backoffMethod: 'GET /access-tokens/{accessTokens}/invalidate',
            parameters: $parameters,
            write: true
        );
    }

    /**
     * Allows an application to inspect access_tokens it has, useful for debugging.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Allows an application to inspect access_tokens it has, useful for debugging. https://api.stackexchange.com/docs/read-access-tokens'
    )]
    public function readAccessTokens(
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
            path: '/access-tokens/' . $this->client->encodedAccessToken(),
            backoffMethod: 'GET /access-tokens/{accessTokens}',
            parameters: $parameters
        );
    }

    /**
     * Get a user's recent network-wide achievements.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get a user\'s recent network-wide achievements. https://api.stackexchange.com/docs/achievements-on-user'
    )]
    public function achievementsOnUser(
        int $id,
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'GET',
            path: '/users/' . $this->client->segment((string) $id) . '/achievements',
            backoffMethod: 'GET /users/{id}/achievements',
            parameters: $parameters,
            authenticated: true
        );
    }

    /**
     * Get a user's recent network-wide achievements.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get a user\'s recent network-wide achievements. https://api.stackexchange.com/docs/me-achievements'
    )]
    public function meAchievements(
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'GET',
            path: '/me/achievements',
            backoffMethod: 'GET /users/{id}/achievements',
            parameters: $parameters,
            authenticated: true
        );
    }

    /**
     * Write operation. Allows an application to de-authorize itself for a set of users.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Write operation. Allows an application to de-authorize itself for a set of users. https://api.stackexchange.com/docs/application-de-authenticate'
    )]
    public function applicationDeAuthenticate(
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
            path: '/apps/' . $this->client->encodedAccessToken() . '/de-authenticate',
            backoffMethod: 'GET /apps/{accessTokens}/de-authenticate',
            parameters: $parameters,
            write: true
        );
    }

    /**
     * Get descriptions of all the errors that the API could return.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get descriptions of all the errors that the API could return. https://api.stackexchange.com/docs/errors'
    )]
    public function errors(
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
            path: '/errors',
            backoffMethod: 'GET /errors',
            parameters: $parameters
        );
    }

    /**
     * Simulate an API error for testing purposes.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Simulate an API error for testing purposes. https://api.stackexchange.com/docs/simulate-error'
    )]
    public function simulateError(
        int $id,
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'GET',
            path: '/errors/' . $this->client->segment((string) $id),
            backoffMethod: 'GET /errors/{id}',
            parameters: $parameters
        );
    }

    /**
     * Create a new filter .
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(description: 'Create a new filter . https://api.stackexchange.com/docs/create-filter')]
    public function createFilter(
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'include' => ['type' => 'array', 'items' => ['type' => 'string']],
                    'exclude' => ['type' => 'array', 'items' => ['type' => 'string']],
                    'base' => ['type' => 'string'],
                    'unsafe' => ['type' => 'boolean'],
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'GET',
            path: '/filters/create',
            backoffMethod: 'GET /filters/create',
            parameters: $parameters
        );
    }

    /**
     * Decode a set of filters, useful for debugging purposes.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Decode a set of filters, useful for debugging purposes. https://api.stackexchange.com/docs/read-filter'
    )]
    public function readFilter(
        string $filters,
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'GET',
            path: '/filters/' . $this->client->segment($filters),
            backoffMethod: 'GET /filters/{filters}',
            parameters: $parameters
        );
    }

    /**
     * Get a user's inbox, outside of the context of a site.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get a user\'s inbox, outside of the context of a site. https://api.stackexchange.com/docs/inbox'
    )]
    public function inbox(
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
            path: '/inbox',
            backoffMethod: 'GET /inbox',
            parameters: $parameters,
            authenticated: true
        );
    }

    /**
     * Get the unread items in a user's inbox, outside of the context of a site.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get the unread items in a user\'s inbox, outside of the context of a site. https://api.stackexchange.com/docs/inbox-unread'
    )]
    public function inboxUnread(
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'page' => ['type' => 'integer'],
                    'pagesize' => ['type' => 'integer'],
                    'since' => ['type' => 'integer'],
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'GET',
            path: '/inbox/unread',
            backoffMethod: 'GET /inbox/unread',
            parameters: $parameters,
            authenticated: true
        );
    }

    /**
     * Get a user's notifications, outside of the context of a site.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get a user\'s notifications, outside of the context of a site. https://api.stackexchange.com/docs/notifications'
    )]
    public function notifications(
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
            path: '/notifications',
            backoffMethod: 'GET /notifications',
            parameters: $parameters,
            authenticated: true
        );
    }

    /**
     * Get a user's unread notifications, outside of the context of a site.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get a user\'s unread notifications, outside of the context of a site. https://api.stackexchange.com/docs/unread-notifications'
    )]
    public function unreadNotifications(
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
            path: '/notifications/unread',
            backoffMethod: 'GET /notifications/unread',
            parameters: $parameters,
            authenticated: true
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
     * Get a user's associated accounts.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(description: 'Get a user\'s associated accounts. https://api.stackexchange.com/docs/associated-users')]
    public function associatedUsers(
        string $ids,
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'page' => ['type' => 'integer'],
                    'pagesize' => ['type' => 'integer'],
                    'types' => ['type' => 'string'],
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'GET',
            path: '/users/' . $this->client->segment($ids) . '/associated',
            backoffMethod: 'GET /users/{ids}/associated',
            parameters: $parameters
        );
    }

    /**
     * Get a user's associated accounts.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(description: 'Get a user\'s associated accounts. https://api.stackexchange.com/docs/me-associated-users')]
    public function meAssociatedUsers(
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'page' => ['type' => 'integer'],
                    'pagesize' => ['type' => 'integer'],
                    'types' => ['type' => 'string'],
                    'filter' => ['type' => 'string', 'description' => 'An official Stack Exchange response filter.']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'GET',
            path: '/me/associated',
            backoffMethod: 'GET /users/{ids}/associated',
            parameters: $parameters,
            authenticated: true
        );
    }

    /**
     * Get the merges a user's accounts has undergone.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get the merges a user\'s accounts has undergone. https://api.stackexchange.com/docs/merge-history'
    )]
    public function mergeHistory(
        string $ids,
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
            path: '/users/' . $this->client->segment($ids) . '/merges',
            backoffMethod: 'GET /users/{ids}/merges',
            parameters: $parameters
        );
    }

    /**
     * Get the merges a user's accounts has undergone.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get the merges a user\'s accounts has undergone. https://api.stackexchange.com/docs/me-merge-history'
    )]
    public function meMergeHistory(
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
            path: '/me/merges',
            backoffMethod: 'GET /users/{ids}/merges',
            parameters: $parameters,
            authenticated: true
        );
    }
}
