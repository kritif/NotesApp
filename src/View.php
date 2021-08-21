<?php

declare(strict_types=1);

namespace NoteApp;

class View
{
  public function render(string $page, array $params = []): void
  {
    $params = $this->escape($params);
    require_once("templates/layout.php");
  }

  private function escape(array $params): array
  {
    $clearParams = [];

    foreach($params as $key => $param) {
      if(is_array($param)) {
        $clearParams[$key] = $this->escape($param);
      } else {
        $clearParams[$key] = htmlentities($param);
      }  
    }

    return $clearParams;
  }
}
