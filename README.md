# 📚stackexchangemcp📚

your [stack exchange](https://api.stackexchange.com/docs), ready for php and ai.

## functions

```text
answers(), answersByIds(), acceptAnswer(), undoAcceptAnswer(), commentsOnAnswers(), deleteAnswer(),
downvoteAnswer(), undoDownvoteAnswer(), editAnswer(), answerFlagOptions(), createAnswerFlag(),
questionsByAnswerIds(), upvoteAnswer(), undoUpvoteAnswer(), recommendAnswer(), undoRecommendAnswer(),
createAnswerSuggestedEdit(), badges(), badgesByIds(), badgesByName(), badgeRecipients(),
badgeRecipientsByIds(), badgesByTag(), collectives(), collectivesBySlug(), questionsByCollective(),
answersByCollective(), tagsByCollective(), usersByCollective(), comments(), commentsByIds(), deleteComment(),
editComment(), createCommentFlag(), commentFlagOptions(), upvoteComment(), undoUpvoteComment(), events(),
info(), posts(), postsByIds(), commentsOnPosts(), createComment(), renderComment(), revisionsByIds(),
postsOnSuggestedEdits(), privileges(), questions(), questionsByIds(), answersOnQuestions(), createAnswer(),
renderAnswer(), questionCloseOptions(), commentsOnQuestions(), deleteQuestion(), downvoteQuestion(),
undoDownvoteQuestion(), editQuestion(), favoriteQuestion(), undoFavoriteQuestion(), createQuestionFlag(),
questionFlagOptions(), linkedQuestions(), relatedQuestions(), createQuestionSuggestedEdit(),
questionsTimeline(), upvoteQuestion(), undoUpvoteQuestion(), createQuestion(), featuredQuestions(),
noAnswerQuestions(), renderQuestion(), unansweredQuestions(), unansweredQuestionsMyTags(), revisionsByGuids(),
search(), advancedSearch(), similar(), excerptSearch(), suggestedEdits(), suggestedEditsByIds(), tags(),
tagsByName(), moderatorOnlyTags(), requiredTags(), tagSynonyms(), faqsByTags(), relatedTags(),
synonymsByTags(), topAnswerersOnTags(), topAskersOnTags(), wikisByTags(), users(), usersByIds(), me(),
answersOnUsers(), meAnswers(), badgesOnUsers(), meBadges(), commentsOnUsers(), meComments(),
commentsByUsersToUser(), meCommentsTo(), favoritesOnUsers(), meFavorites(), mentionsOnUsers(), meMentioned(),
usersNetworkActivity(), meNetworkActivity(), userNotifications(), meNotifications(),
userUnreadNotifications(), meUnreadNotifications(), postsOnUsers(), mePosts(), privilegesOnUsers(),
mePrivileges(), questionsOnUsers(), meQuestions(), featuredQuestionsOnUsers(), meFeaturedQuestions(),
noAnswerQuestionsOnUsers(), meNoAnswerQuestions(), unacceptedQuestionsOnUsers(), meUnacceptedQuestions(),
unansweredQuestionsOnUsers(), meUnansweredQuestions(), reputationOnUsers(), meReputation(),
reputationHistory(), meReputationHistory(), fullReputationHistory(), meFullReputationHistory(),
suggestedEditsOnUsers(), meSuggestedEdits(), tagsOnUsers(), meTags(), topUserAnswersInTags(),
meTagsTopAnswers(), topUserQuestionsInTags(), meTagsTopQuestions(), tagPrefsOnUser(), meTagPrefs(),
editTagPrefsOnUser(), meEditTagPrefs(), timelineOnUsers(), meTimeline(), topAnswerTagsOnUsers(),
meTopAnswerTags(), topQuestionTagsOnUsers(), meTopQuestionTags(), topTagsOnUsers(), meTopTags(), moderators(),
electedModerators(), userInbox(), meInbox(), userUnreadInbox(), meUnreadInbox(), invalidateAccessTokens(),
readAccessTokens(), achievementsOnUser(), meAchievements(), applicationDeAuthenticate(), errors(),
simulateError(), createFilter(), readFilter(), inbox(), inboxUnread(), notifications(), unreadNotifications(),
sites(), associatedUsers(), meAssociatedUsers(), mergeHistory(), meMergeHistory()
```

## installation

```sh
composer require vielhuber/stackexchangemcp
```

## php

```php
use vielhuber\stackexchangemcp\stackexchangemcp;

$stack = new stackexchangemcp();
$stack->sites();
$stack->timelineOnUsers(ids: '22656', site: 'stackoverflow');
$stack->questions(site: 'meta.stackoverflow', parameters: ['pagesize' => 10]);
$stack->createQuestion(parameters: [
    'title' => 'your own question',
    'body' => 'your own markdown',
    'tags' => ['php']
]);
```

all documented api v2.3 endpoints, including `/me` aliases, are covered. choose any supported main or meta site through `site` or `STACKEXCHANGE_SITE` (default: `stackoverflow`). network methods do not send a site. parameters and responses retain the official api structure, including `has_more`, quotas and filters. respect pagination and avoid polling identical requests more than once per minute.

public reads work anonymously. register a [stack apps application](https://stackapps.com/apps/oauth), then set `STACKEXCHANGE_API_KEY` and `STACKEXCHANGE_ACCESS_TOKEN` for authorized calls. writing needs `write_access` and the app's registered stack apps post; private methods may need additional scopes. site privileges and quality checks still apply. [ai-generated content is prohibited on stack overflow](https://stackoverflow.com/help/gen-ai-policy).

for oauth pkce, use `Authorization(clientId: ..., redirectUri: ..., clientSecret: ...)`, `url(state: ..., codeChallenge: ..., scopes: [...])` and `exchangeCode(code: ..., codeVerifier: ...)`. persist the verifier and verify state yourself. token exchange is php-only. token-management tools act on the configured token and redact it in responses.

## mcp

the same api functions are tools (snake_case names). configure your client:

```json
{
    "mcpServers": {
        "stackexchange": {
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

stdio needs no mcp token. for http, set a separate `MCP_TOKEN` in `.env`, require https and deny direct access to `.env`. never expose the server without authentication.

api failures are surfaced without automatic retries. after an uncertain write, read the current state before repeating it. credentials are never included in error messages.

## development

```sh
composer install
npm install
composer generate
composer lint
npm run format:check
```

endpoint metadata lives in `resources/endpoints.json`; `composer generate` rebuilds the php tools from it.
