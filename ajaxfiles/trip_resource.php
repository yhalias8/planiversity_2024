<?php
include '../config.ini.php';
require ('../class/class.TripResource.php');

class TripResourceController {
    private $resource;
    private $authService;

    public function __construct($authService)
    {
        $this->resource = new TripResource();
        $this->authService = $authService;
    }

    public function handle() {
        header('Content-Type: application/json; charset=utf-8');

        if (!$this->authService->isLogged()) {
            $res['txt'] = '';
            $res['error'] = 'You do not have access to this function';
            echo json_encode($res);
            exit;
        }

        $request_uri = $_SERVER['REQUEST_URI'];
        $parts = explode('/', trim($request_uri, '/'));

        $id = null;

        if (is_numeric(end($parts))) {
            $id = end($parts);
        }

        $method = $_SERVER['REQUEST_METHOD'];

        switch ($method) {
            case 'GET':
                if ($id) {
                    $this->show($id);
                    break;
                }
                $this->index();
                break;
            case 'POST':
                if ($id) {
                    $this->update($id);
                    break;
                }
                $this->store();
                break;
            case 'DELETE':
                $this->destroy($id);
                break;

            default:
                $this->methodNotAllowed();
                break;
        }
    }

    function index() {
        if (!empty($_GET['tripId'])) {
            $data = $this->resource->get_resources_from_trip($_GET['tripId']);

            http_response_code(200);

            echo json_encode([
                'status' => true,
                'data' => $data
            ]);
        } else {
            echo json_encode([
                'status' => 'false',
                'data' => []
            ]);
        }
    }

    function show($id) {
        $resource = $this->resource->find_by_id($id);

        if ($this->resource->error) {
            http_response_code(422);

            echo json_encode([
                'status' => false,
                'message' => $this->resource->error
            ]);

            return;
        }


        if (!$resource) {
            http_response_code(422);

            echo json_encode([
                'status' => false,
                'message' => 'Resource was not found.'
            ]);

            return;
        }

        http_response_code(200);

        echo json_encode([
            'status' => true,
            'message' => 'OK.',
            'data' => [
                'resource' => $resource
            ]
        ]);
    }

    function store() {
        $trip_id = $_POST['resource_trip_id'];
        $title = $_POST['resource_title'];
        $address = $_POST['resource_address'];
        $lat = $_POST['resource_lat'];
        $lng = $_POST['resource_lng'];
        $type = $_POST['resource_type'];
        $custom = $_POST['resource_custom'] ?? 1;


        $resId = $this->resource->create($trip_id, $title, $address, $lat, $lng, $type, $custom);

        if ($this->resource->error) {
            http_response_code(422);

            echo json_encode([
                'status' => false,
                'message' => $this->resource->error
            ]);

            return;
        }

        http_response_code(201);

        echo json_encode([
            'status' => true,
            'message' => 'Created.',
            'data' => [
                'resource_id' => $resId
            ]
        ]);
    }

    function destroy($id) {
        if (!$id) {
            echo json_encode([
                'status' => false,
                'message' => 'Remove param should be present.'
            ]);
        }

        $this->resource->delete($id);

        http_response_code(200);

        echo json_encode([
            'status' => true
        ]);
    }

    function update($id) {
        if (!$id) {
            echo json_encode([
                'status' => false,
                'message' => 'Update param should be present.'
            ]);
        }

        $trip_id = $_POST['resource_trip_id'];
        $title = $_POST['resource_title'];
        $address = $_POST['resource_address'];
        $lat = $_POST['resource_lat'];
        $lng = $_POST['resource_lng'];
        $type = $_POST['resource_type'];
        $custom = $_POST['resource_custom'] ?? 0;

        $this->resource->update($id, $trip_id, $title, $address, $lat, $lng, $type, $custom);

        if ($this->resource->error) {
            http_response_code(422);

            echo json_encode([
                'status' => false,
                'message' => $this->resource->error
            ]);

            return;
        }

        http_response_code(200);

        echo json_encode([
            'status' => true,
            'message' => 'Updated.',
        ]);
    }

    function methodNotAllowed() {
        http_response_code(405);

        echo json_encode([
            'status' => false,
            'message' => 'Unsupported action.'
        ]);
    }
}

(new TripResourceController($auth))->handle();



