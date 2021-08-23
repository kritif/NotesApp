<?php

declare(strict_types=1);

namespace NoteApp\Controller;

class NoteController extends AbstractController
{  
  private const PAGE_SIZE = 10;

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
    $pageNumber = (int) $this->request->getParam('page_number', 1);
    $pageSize = (int) $this->request->getParam('page_size', self::PAGE_SIZE);
    $sortBy = $this->request->getParam('sortby', 'title');
    $sortOrder = $this->request->getParam('sortorder', 'desc');
    $phrase = $this->request->getParam('phrase');

    if(!in_array($pageSize, [1, 5, 10, 25])) {
      $pageSize = self::PAGE_SIZE;
    }

    if($phrase) {
      $notesCount = $this->database->getSearchCount($phrase);
      $notes = $this->database->searchNotes($phrase, $sortBy, $sortOrder, $pageSize, $pageNumber);
    } else {
      $notesCount = $this->database->getNotesCount();
      $notes = $this->database->getNoteList($sortBy, $sortOrder, $pageSize, $pageNumber);
    }  

    $this->view->render(
      'list', 
      [
        'page' => [
          'number' => $pageNumber, 
          'size' => $pageSize, 
          'pages' => (int) ceil($notesCount/$pageSize),
          'phrase' => $phrase
        ],
        'sort' => ['by' =>  $sortBy, 'order' =>  $sortOrder],
        'before' => $this->request->getParam('before'),
        'error' => $this->request->getParam('error'),
        'notes' => $notes
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
    
    return $this->database->getNote($noteId);
  }
}
