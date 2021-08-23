<?php

declare(strict_types=1);

namespace NoteApp\Controller;

use NoteApp\Exception\NotFoundException;

class NoteController extends AbstractController
{  
  public function createAction(): void
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

  public function showAction(): void
  {
    $this->view->render(
      'show', 
      ['note' => $this->getNoteData()]);
  }

  public function listAction(): void
  {      
    
    $sortBy = $this->request->getParam('sortby', 'title');
    $sortOrder = $this->request->getParam('sortorder', 'desc');

    $this->view->render(
      'list', 
      [
        'sort' => ['by' =>  $sortBy, 'order' =>  $sortOrder],
        'before' => $this->request->getParam('before'),
        'error' => $this->request->getParam('error'),
        'notes' => $this->database->getNoteList($sortBy,$sortOrder)
      ]
    );
  }

  public function deleteAction(): void
  { 
    if($this->request->isPost()) {
      $noteId = (int) $this->request->postParam('id');
      $this->database->deleteNote($noteId);
      $this->redirect('/',['before' => 'deleted']);
    } else {
      $this->view->render(
        'delete', 
        ['note' => $this->getNoteData()]
      );   
    }
  }

  public function editAction(): void
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
   
      $this->view->render(
        'edit', 
        ['note' => $this->getNoteData()]
      );  
    }

  }

  private function getNoteData(): array
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

    return $note;
  }
}
