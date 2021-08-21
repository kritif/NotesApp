<?php

declare(strict_types=1);

namespace NoteApp\Controller;

use NoteApp\Exception\NotFoundException;

class NoteController extends AbstractController
{  
  public function createAction()
  {
    if ($this->request->hasPost()) {         
      $this->database->createNote(
        [
          'title' => $this->request->postParam('title'),
          'description' => $this->request->postParam('description')
        ]
      );
      header('Location: /?before=created');
    }
    $this->view->render('create');
  }

  public function showAction()
  {
    $noteId = (int) $this->request->getParam('id');
    if(!$noteId) {
      dump($noteId);
      header('Location: /?error=missingNoteId');
      exit;
    }
    
    try {
      $note = $this->database->getNote($noteId);
    } catch(NotFoundException $e) {
      header('Location: /?error=noteNotFound');
      exit;
    }

    $this->view->render(
      'show', 
      ['note' => $note]);
  }

  public function listAction()
  {      
    $this->view->render(
      'list', 
      [
        'before' => $this->request->getParam('before'),
        'error' => $this->request->getParam('error'),
        'notes' => $this->database->getNoteList()
      ]
    );
  }
}
