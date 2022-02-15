<supplies
    project_id="{{ $field['project']->id }}"
    :supplies_statuses="{{ json_encode($field['supplies_statuses']) }}"
    :vat_rates="{{json_encode($field['supplies_tva_rates']) }}"
></supplies>
