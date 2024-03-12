@livewire('chatbot.actions', ['assistantId' => $entity->id, 'canTrain' => $entity->trainRows()->count() > 0])
