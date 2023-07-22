<?php
return [
    'title' => [
        'name' => 'Xitara Gallery',
        'description' => 'Bock für das Xitara Galerie-Plugin',
    ],
    'heading'          => [
        'label'   => 'Überschrift',
        'comment' => 'Kurze und prägnante Überschrift um die Seite einzuleiten',
    ],
    'subheading'       => [
        'label'   => 'Unterüberschrift',
        'comment' => 'Kurzer Text um die Überschrift zu ergänzen. Optional',
    ],
    'text'             => [
        'label'   => 'Text',
        'comment' => '',
    ],
    'gallery' => [
        'label' => 'Galerie auswählen',
        'comment' => '',
    ],
    'is_container' => [
        'label' => 'In einem Container anzeigen',
        'comment' => 'Wenn AUS erfolgt die Anzeige über die gesamte Breite der Seite',
    ],
    'is_active'        => [
        'label'   => 'Block aktiv',
        'comment' => 'Wenn ein Block nicht aktiv is, wird er auf der Seite nicht ausgegeben',
    ],
    'classes'          => [
        'label'   => 'Klassen',
        'comment' => 'CSS-Klassen durch Leerzeichen getrennt. Optional',
    ],
    'alias'            => [
        'label'   => 'Alias des Blocks',
        'comment' => 'Diese Alias wird benötigt, wenn der Block im Layout einzeln ausgespielt werden soll. Gleichzeitig dient dieser Wert als Anker um ihn per Link anzuspringen. <span style="color: red;">Der Alias darf auf der ganzen Seite nur einmal vorkommen! Diesen Wert nur ändern, wenn du weisst, was du tust!</span>',
    ],
];
