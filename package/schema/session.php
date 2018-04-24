<?php //-->
return array (
  'redirect_uri' => '/admin/system/schema/search',
  'singular' => 'Session',
  'plural' => 'Sessions',
  'name' => 'session',
  'icon' => 'fas fa-user-secret',
  'detail' => 'Collection of REST Sessions',
  'fields' => 
  array (
    0 => 
    array (
      'disable' => '1',
      'label' => 'Token',
      'name' => 'token',
      'field' => 
      array (
        'type' => 'text',
      ),
      'validation' => 
      array (
        0 => 
        array (
          'method' => 'required',
          'message' => 'Token is required',
        ),
      ),
      'list' => 
      array (
        'format' => 'none',
      ),
      'detail' => 
      array (
        'format' => 'none',
      ),
      'default' => '',
      'searchable' => '1',
    ),
    1 => 
    array (
      'disable' => '1',
      'label' => 'Secret',
      'name' => 'secret',
      'field' => 
      array (
        'type' => 'text',
      ),
      'validation' => 
      array (
        0 => 
        array (
          'method' => 'required',
          'message' => 'Secret is required',
        ),
      ),
      'list' => 
      array (
        'format' => 'none',
      ),
      'detail' => 
      array (
        'format' => 'none',
      ),
      'default' => '',
      'searchable' => '1',
    ),
    2 => 
    array (
      'disable' => '1',
      'label' => 'Status',
      'name' => 'status',
      'field' => 
      array (
        'type' => 'text',
      ),
      'validation' => 
      array (
        0 => 
        array (
          'method' => 'required',
          'message' => 'Status is required',
        ),
      ),
      'list' => 
      array (
        'format' => 'none',
      ),
      'detail' => 
      array (
        'format' => 'none',
      ),
      'default' => '',
      'searchable' => '1',
      'filterable' => '1',
    ),
    3 => 
    array (
      'disable' => '1',
      'label' => 'Type',
      'name' => 'type',
      'field' => 
      array (
        'type' => 'text',
      ),
      'list' => 
      array (
        'format' => 'none',
      ),
      'detail' => 
      array (
        'format' => 'none',
      ),
      'default' => '',
      'searchable' => '1',
      'filterable' => '1',
    ),
    4 => 
    array (
      'disable' => '1',
      'label' => 'Active',
      'name' => 'active',
      'field' => 
      array (
        'type' => 'active',
      ),
      'list' => 
      array (
        'format' => 'hide',
      ),
      'detail' => 
      array (
        'format' => 'hide',
      ),
      'default' => '1',
      'filterable' => '1',
      'sortable' => '1',
    ),
    5 => 
    array (
      'disable' => '1',
      'label' => 'Created',
      'name' => 'created',
      'field' => 
      array (
        'type' => 'created',
      ),
      'list' => 
      array (
        'format' => 'date',
        'parameters' => 'Y-m-d H:i:s',
      ),
      'detail' => 
      array (
        'format' => 'date',
        'parameters' => 'Y-m-d H:i:s',
      ),
      'default' => 'NOW()',
      'sortable' => '1',
    ),
    6 => 
    array (
      'disable' => '1',
      'label' => 'Updated',
      'name' => 'updated',
      'field' => 
      array (
        'type' => 'updated',
      ),
      'list' => 
      array (
        'format' => 'date',
        'parameters' => 'Y-m-d H:i:s',
      ),
      'detail' => 
      array (
        'format' => 'date',
        'parameters' => 'Y-m-d H:i:s',
      ),
      'default' => 'NOW()',
      'sortable' => '1',
    ),
  ),
  'relations' => 
  array (
    0 => 
    array (
      'many' => '1',
      'name' => 'app',
    ),
  ),
  'suggestion' => '',
  'disable' => '1',
);