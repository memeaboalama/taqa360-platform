<?php

namespace App\Websocket;


use BeyondCode\LaravelWebSockets\HttpApi\Controllers\FetchChannelController;
use BeyondCode\LaravelWebSockets\HttpApi\Controllers\FetchChannelsController;
use BeyondCode\LaravelWebSockets\HttpApi\Controllers\FetchUsersController;
use BeyondCode\LaravelWebSockets\HttpApi\Controllers\TriggerEventController;
use BeyondCode\LaravelWebSockets\Server\Logger\WebsocketsLogger;
use Ratchet\WebSocket\WsServer;

class Router extends \BeyondCode\LaravelWebSockets\Server\Router
{
    public function echo()
    {
        $this->get('/app/{appKey}', MyCustomWebSocketHandler::class);

        $this->post('/apps/{appId}/events', TriggerEventController::class);
        $this->get('/apps/{appId}/channels', FetchChannelsController::class);
        $this->get('/apps/{appId}/channels/{channelName}', FetchChannelController::class);
        $this->get('/apps/{appId}/channels/{channelName}/users', FetchUsersController::class);
    }

    /**
     * @param string $action
     * @return WsServer
     */
    protected function createWebSocketsServer(string $action): WsServer
    {
        $app = app($action);

        if (WebsocketsLogger::isEnabled() && !$this->isCustomAction($action)) {
            $app = WebsocketsLogger::decorate($app);
        }

        return new WsServer($app);
    }

    /**
     * @param string $action
     * @return bool
     */
    private function isCustomAction(string $action): bool
    {
        return false !== array_search($action, $this->customRoutes->all());
    }
}
