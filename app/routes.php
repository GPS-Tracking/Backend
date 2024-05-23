<?php

declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Hello world!');
        return $response;
    });

    $app->group('/users', function (Group $group) {
        $group->get('', ListUsersAction::class);
        $group->get('/{id}', ViewUserAction::class);
    });
    $app->get('/data/getAll', function (Request $request, Response $response) {
        try {
            $db = $this->get(PDO::class);
            $sth = $db->prepare("SELECT * FROM `map-tracking`");
            $sth->execute();
            $data = $sth->fetchAll(PDO::FETCH_ASSOC);
            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
        } catch (PDOException $e) {
            $data = array(
                "status" => "PDOException",
                "message" => $e->getMessage()
            );
            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    });

    $app->get('/data/device', function (Request $request, Response $response) {
        try {
            $db = $this->get(PDO::class);
            $sth = $db->prepare("SELECT Name, Latitude, Longitude FROM `map-tracking`");
            $sth->execute();
            $data = $sth->fetchAll(PDO::FETCH_ASSOC);
            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
        } catch (PDOException $e) {
            $data = array(
                "status" => "PDOException",
                "message" => $e->getMessage()
            );
            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    });

    $app->get('/status/aman', function (Request $request, Response $response) {
        try {
            $db = $this->get(PDO::class);
            $sth = $db->prepare("SELECT COUNT(*) AS Jumlah_Device_Aman FROM `map-tracking` WHERE Status = 'Aman'");
            $sth->execute();
            $data = $sth->fetchAll(PDO::FETCH_ASSOC);
            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
        } catch (PDOException $e) {
            $data = array(
                "status" => "PDOException",
                "message" => $e->getMessage()
            );
            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    });

    $app->get('/status/warning', function (Request $request, Response $response) {
        try {
            $db = $this->get(PDO::class);
            $sth = $db->prepare("SELECT COUNT(*) AS Jumlah_Device_Warning FROM `map-tracking` WHERE Status = 'Warning'");
            $sth->execute();
            $data = $sth->fetchAll(PDO::FETCH_ASSOC);
            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
        } catch (PDOException $e) {
            $data = array(
                "status" => "PDOException",
                "message" => $e->getMessage()
            );
            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    });

    $app->get('/status/SOS', function (Request $request, Response $response) {
        try {
            $db = $this->get(PDO::class);
            $sth = $db->prepare("SELECT COUNT(*) AS Jumlah_Device_SOS FROM `map-tracking` WHERE Status = 'SOS'");
            $sth->execute();
            $data = $sth->fetchAll(PDO::FETCH_ASSOC);
            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
        } catch (PDOException $e) {
            $data = array(
                "status" => "PDOException",
                "message" => $e->getMessage()
            );
            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    });

    $app->get('/status/noStatus', function (Request $request, Response $response) {
        try {
            $db = $this->get(PDO::class);
            $sth = $db->prepare("SELECT COUNT(*) AS Jumlah_Device_noStatus FROM `map-tracking` WHERE Status IS NULL");
            $sth->execute();
            $data = $sth->fetchAll(PDO::FETCH_ASSOC);
            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (PDOException $e) {
            $data = array(
                "status" => "PDOException",
                "message" => $e->getMessage()
            );
            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    });

    $app->delete('/data/delete', function (Request $request, Response $response, $args) {
        $nomor = $request->getQueryParams()['id'];
        try {
            $db = $this->get(PDO::class);
            $sth = $db->prepare("DELETE FROM `map-tracking` WHERE id = :nomor");
            $sth->bindParam(':nomor', $nomor);
            $sth->execute();

            // Respond with success message or appropriate data
            $data = array(
                "status" => "Success",
                "message" => "Item deleted successfully"
            );
            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (PDOException $e) {
            $data = array(
                "status" => "PDOException",
                "message" => $e->getMessage()
            );
            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    });

    $app->put('/data/updateStatus/{id}', function (Request $request, Response $response, $args) {
        $id = $args['id'];
        $data = $request->getParsedBody();
    
        try {
            $db = $this->get(PDO::class);
            $sth = $db->prepare("UPDATE `map-tracking` SET Status = :status WHERE id = :id");
            $sth->bindParam(':status', $data['status']);
            $sth->bindParam(':id', $id);
            $sth->execute();
    
            $responseData = array(
                "status" => "Success",
                "message" => "Status updated successfully"
            );
            $response->getBody()->write(json_encode($responseData));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (PDOException $e) {
            $responseData = array(
                "status" => "PDOException",
                "message" => $e->getMessage()
            );
            $response->getBody()->write(json_encode($responseData));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    });
    
    $app->put('/data/updateCatatan/{id}', function (Request $request, Response $response, $args) {
        $id = $args['id'];
        $data = $request->getParsedBody();
    
        try {
            $db = $this->get(PDO::class);
            $sth = $db->prepare("UPDATE `map-tracking` SET Catatan = :catatan WHERE id = :id");
            $sth->bindParam(':catatan', $data['catatan']);
            $sth->bindParam(':id', $id);
            $sth->execute();
    
            $responseData = array(
                "status" => "Success",
                "message" => "Catatan updated successfully"
            );
            $response->getBody()->write(json_encode($responseData));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (PDOException $e) {
            $responseData = array(
                "status" => "PDOException",
                "message" => $e->getMessage()
            );
            $response->getBody()->write(json_encode($responseData));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    });

    $app->get('/api/data/getFilteredData', function (Request $request, Response $response) {
        try {
            $db = $this->get(PDO::class);
            $start = $request->getQueryParams()['start'];
            $end = $request->getQueryParams()['end'];

            $sth = $db->prepare("SELECT * FROM `map-tracking` WHERE DateColumn BETWEEN :start AND :end");
            $sth->bindParam(':start', $start);
            $sth->bindParam(':end', $end);
            $sth->execute();

            $data = $sth->fetchAll(PDO::FETCH_ASSOC);

            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (PDOException $e) {
            $data = array(
                "status" => "PDOException",
                "message" => $e->getMessage()
            );
            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    });
    
};