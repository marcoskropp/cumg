<?php

/**
 * <b>View.class</b> [HELPER MVC]
 * Esta classe é responsável por fazer o load, carregar o template.
 * Também é reposnsável por povoar e exibir as views em nosso sistema.
 * 
 * Esta classe tem todos os seus métodos e atributos estáticos -> static
 */
class View {

    private static $dados; //onde vamos carregar os dados
    private static $keys; //onde vamos carregar nossos links
    private static $values; //para carregar os valroes 
    private static $template; //para carregar nossa view

    /**
     * <b>load: responsável por carregar o template view</b>
     * Basta informar o caminho onde a view se encontra para fazer o load
     */

    public static function load(string $template) {
        self::$template = $template;
        self::$template = file_get_contents(self::$template); //carregamos o arquivo em nosso template
    }

    /**
     * <b>show: responsável por exibir a view</b>
     * Deve ser chamado sempre após carregar o template, nunca antes
     */
    public static function show(array $dados) {
        self::setKeys($dados);
        self::setValues();
        return self::showView();
    }

    /**
     * <b>request: carregar php view</b>
     * Apenas para arquivos de inclusão.
     */
    public static function request($file, array $data) {
        extract($data);
        require "{$file}.inc.php";
    }

    //metodos privates
    //Executa o tratamento dos links para o replace
    private static function setKeys($data) {
        self::$dados = $data;
        self::$keys = explode("&", "#_" . implode("_#&#_", array_keys(self::$dados)) . "_#");
    }

    //insere os valores nas chaves da view
    private static function setValues() {
        self::$values = array_values(self::$dados);
    }

    //exibe o template view com echo;
    private static function showView() {
        return str_replace(self::$keys, self::$values, self::$template);
    }

}
