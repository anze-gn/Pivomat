<?php

require_once 'HTML/QuickForm2/Renderer.php';

class CustomRenderer {

    /**
     * Call this method to get singleton
     */
    public static function instance()
    {
        static $instance = false;
        if( $instance === false ) {
            // Late static binding (PHP 5.3+)
            $instance = HTML_QuickForm2_Renderer::factory('default')
                ->setOption(array(
                    'group_hiddens' => true,
                    'required_note' => 'Polja označena z zvezdico ( <b>*</b> ) so obvezna.',
                    'errors_prefix' => 'Vneseni so bili napačni podatki:',
                    'errors_suffix' => 'Prosimo popravite podatke v teh poljih.',
                    'group_errors'  => true
                ))
                ->setTemplateForClass(
                    'html_quickform2_element',
                    '<div class="form-group row">
                        <qf:label><label for="{id}" class="col-sm-2 col-form-label"><qf:required><span class="required">*</span></qf:required> {label}</label></qf:label>
                        <div class="col-sm-10 mx-auto">
                            {element}
                        </div>
                    </div>'
                )->setTemplateForClass(
                    'html_quickform2',
                    '<div class="quickform">
                        <qf:reqnote>
                            <div class="reqnote text-center my-4"><h5>{reqnote}</h5></div>
                        </qf:reqnote>
                        {errors}
                        <form{attributes}>
                            {hidden}
                            {content}
                        </form>
                        <script>document.querySelector("form").reset()</script>
                    </div>'
                );
        }

        return $instance;
    }

    /**
     * Make constructor private, so nobody can call "new Class".
     */
    private function __construct() {}

    /**
     * Make clone magic method private, so nobody can clone instance.
     */
    private function __clone() {}

    /**
     * Make sleep magic method private, so nobody can serialize instance.
     */
    private function __sleep() {}

    /**
     * Make wakeup magic method private, so nobody can unserialize instance.
     */
    private function __wakeup() {}

}