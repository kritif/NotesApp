<?php

declare(strict_types=1);

namespace NoteApp;

require_once("View.php");

class Controller
{
  private const DEFAULT_ACTION = 'list';
  private array $request;
  private View $view;

  public function __construct(array $request)
  {
    $this->request = $request;
    $this->view = new View();
  }

  public function run(): void
  { 
    $viewParams = [];

    switch ($this->action()) {
      case 'create':
        $page = 'create';
        $created = false;
        $data = $this->getRequestPost();
        if (!empty($data)) {
          $viewParams = [
            'title' => $data['title'] ?? '',
            'description' => $data['description'] ?? ''
          ];
          $created = true;
        }
        $viewParams['created'] = $created;
        break;
      case 'show':
        $viewParams = [
          'title' => 'Title',
          'description' => 'Description'
        ];
        break;
      default:
        $page = 'list';
        $viewParams['resultList'] = 'list';
        break;
    };

    $this->view->render($page, $viewParams);
  }

  private function getRequestPost(): array
  {
    return $this->request['post'] ?? [];
  }

  private function getRequestGet(): array
  {
    return $this->request['get'] ?? [];
  }

  private function action(): string
  {
    $data = $this->getRequestGet();
    return $data['action'] ?? self::DEFAULT_ACTION;
  }
}
