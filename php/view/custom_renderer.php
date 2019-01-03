<?php

require_once 'HTML/QuickForm2/Renderer.php';

$custom_renderer = HTML_QuickForm2_Renderer::factory('default')
    ->setOption(array(
        'group_hiddens' => true,
        'required_note' => 'Polja označena z zvezdico (<em>*</em>) so obvezna.',
        'errors_prefix' => 'Vneseni so bili napačni podatki:',
        'errors_suffix' => 'Prosimo popravite podatke v teh poljih.',
        'group_errors'  => true
    ))
    ->setTemplateForClass(
        'html_quickform2_element',
        '<div class="form-group row">
            <qf:label><label for="{id}"><qf:required><span class="required">*</span></qf:required> {label}</label></qf:label>
            {element}
        </div>'
    )->setTemplateForClass(
        'html_quickform2',
        '<div class="quickform">
            {errors}
            <form{attributes}>
                {hidden}
                {content}
            </form>
            <qf:reqnote>
                <div class="reqnote">{reqnote}</div>
            </qf:reqnote>
        </div>'
    );