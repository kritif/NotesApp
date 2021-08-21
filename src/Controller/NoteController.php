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
      $this->redirect('/',['before' => 'created']);
    }
    $this->view->render('create');
  }

  public function showAction()
  {
    $noteId = (int) $this->request->getParam('id');
    if(!$noteId) {
      $this->redirect('/',['error' => 'missingNoteId']);
      exit;
    }
    
    try {
      $note = $this->database->getNote($noteId);
    } catch(NotFoundException $e) {
      $this->redirect('/',['error' => 'noteNotFound']);
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

  public function editAction()
  {
    if ($this->request->isPost()) {
      $noteId = (int) $this->request->postParam('id');

      $noteData = [
        'title' => $this->request->postParam('title'),
        'description' => $this->request->postParam('description')
      ];
      $this->database->editNote($noteId,$noteData);
      $this->redirect('/',['before' => 'edited']);

    } else if($this->request->isGet()) {
      $noteId = (int) $this->request->getParam('id');
      try {
        $note = $this->database->getNote($noteId);
      } catch(NotFoundException $e) {
        $this->redirect('/',['error' => 'noteNotFound']);
      }
  
      $this->view->render(
        'edit', 
        ['note' => $note]
      );  
    }
    if(!$noteId) {
      $this->redirect('/',['error' => 'missingNoteId']);
    } 
  }
}
