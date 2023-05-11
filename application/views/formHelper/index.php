<?php 
// -16/12/2022 AL-

// fonction helper pas accès hors dev
class CreateForm {
    private $form;
    private $formClass = null;

    private function cleanStr($string){
        return preg_replace('/[^A-Za-z0-9\-\'\s\<\>\!\p{L}]/u', '', $string); // Enlève les tous les caractères spéciaux sauf les accents
    }

    /**
     * Table de correspondance pour récupérer le name à envoyer en fonction du nom saisi en front
     */
    private static function nameCorrespondance($userField){
        $dbField  ='';
        switch($userField){
            case 'nom_prenom':
            case 'nom_filleul':
                $dbField = 'lead_name';
                break;
            case 'nom_parrain':
                $dbField = 'lead_name_parrain';
                break;
            case 'numeroClient_parrain':
                $dbField = 'lead_idparrain';
                break;
            case 'telephone':
                $dbField = 'lead_phone';
                break;
            case 'email':
                $dbField = 'lead_mail';
                break;
            case 'numero_client':
                $dbField = 'lead_idclient';
                break;
            case 'adresse':
                $dbField = 'lead_adress';
                break;
            case 'ville':
                $dbField = 'lead_city';
                break;
            case 'code_postal':
                $dbField = 'lead_zip';
                break;
            case 'contratEnseigne':
                $dbField = 'lead_contrat';
                break;
            case 'natureIntermediaire':
                $dbField = 'lead_natureIntermediaire';
                break;
            case 'horaires':
                $dbField = 'lead_time';
                break;
            default :
                $dbField ='Erreur dans le nommage du champs.';
        }
        return $dbField;
    }

    /**
     * Fonction pour ajouter les attributs à l'input
     * @param array $attributes
     */
    private function addAttributes(array $attributes){
        $allAttributs = '';
        foreach($attributes as $attributName => $attributValue){
            $allAttributs .= " " .$attributName."='".$attributValue ."'";
        }
        return $allAttributs;
    }

    /**
     * Fonction pour ajouter les options au select
     * @param array $options
     */
    private function addOptions(array $options){
        $allSelect = '';
        foreach($options as $optionName => $optionValue){
            $allSelect .= "<option value='".$optionName."'>".$optionValue."</option>";
        }
        return $allSelect;
    }

    /**
     * Fonction pour récupérer les datas disponibles en BDD et pré-remplir le formulaire
     */
    private function fetchFieldValue(){
        global $row;
        $fieldValue  ='';
        switch(true){
            case isset($row['prenom']) && isset($row['nom']):
                $fieldValue = $row['prenom'].' '. $row['nom'];
                break;
            case isset($row['telephoneMobile']):
                $fieldValue = $row['telephoneMobile'];
                break;
            case isset($row['rue']):
                $fieldValue = $row['rue'];
                break;
            case isset($row['codePostal']):
                $fieldValue = $row['codePostal'];
                break;
            case isset($row['commune']):
                $fieldValue = $row['commune'];
                break;
            default :
                $fieldValue  ='';
        }
        return $fieldValue;
    }

    /**
     * Fonction pour créer un input
     * @param string $type
     * @param string $fieldName
     * @param array $attributes
     * retourne la propriété form de la classe modifiée
     */
    public function input(string $type='text',string $fieldName,array $attributes=null){
        global $idClient, $row;

        // check du champ dans la table nameCorrespondance
        $dbField = self::nameCorrespondance($fieldName);

        // condition particulière lorsque le champs lead-idClient est en hidden
        if($type ==='hidden' && $fieldName ==='idClient'){
            $idClient = (isset($idClient) && $idClient != '') ? $idClient : '';
            $this->form .= "<fieldset class='form-control'>
                        <input type='".$type."' name='".$dbField."' value='".$idClient."' />
                    </fieldset>";
        }
        else{
            ($type ==='text')||($type ==='mail') ||($type ==='tel')  ? $this->form .= "<fieldset class='form-control'>" : '';
            $this->form .= "<input type='".$type."' name='".$dbField."' value='".$this->fetchFieldValue() ."' ";
            ($type != 'hidden') ? $this->form .= "id='".$dbField."'" : $this->form .= "";
            if(isset($attributes)) $this->form .= $this->addAttributes($attributes);
            ($type ==='text')||($type ==='mail')||($type ==='tel')  ? $this->form .="/> </fieldset>" : $this->form .="/>";
        }
        return $this;
    }

    /**
     * Fonction pour créer une checkbox
     * @param string $fieldName
     * @param string $label
     * @param array $attributes
     * retourne la propriété form de la classe modifiée
     */
    public function checkbox(string $fieldName, string $label=null, array $attributes=null){
        // check du champ dans la table nameCorrespondance
        $dbField = self::nameCorrespondance($fieldName);

        $this->form .= "<fieldset class='checkbox-".$fieldName."'>";
        $this->form .= "<input type='checkbox' id='".$dbField."' name='checkbox-".$dbField."'";
        if(isset($attributes)) $this->form .= $this->addAttributes($attributes);
        $this->form .= "/>";
        isset($label) ? $this->form .= "<label for='".$dbField."'>".$label."</label>": '';

        $this->form .= "<input type='hidden' name='".$dbField."' value='0'/>
                        </fieldset>
                        <div class='errorInfo-".$fieldName."'></div>";
        return $this;
        }

    /**
     * Fonction pour créer un select
     * @param string $fieldName
     * @param array $attributes
     * @param array $options
     * retourne la propriété form de la classe modifiée
     */
    public function select(string $fieldName, array $attributes=null, array $options=null){
        // check du champ dans la table nameCorrespondance
        $dbField = self::nameCorrespondance($fieldName);

        $this->form .= "<fieldset class='checkbox-".$fieldName."'>";
        $this->form .= "<select name='".$dbField ."' id='".$dbField ."' ";
        if(isset($attributes)) $this->form .= $this->addAttributes($attributes);
        $this->form .= ">";
        if(isset($options))$this->form .= $this->addOptions($options); 
        $this->form .= "</select>";
        $this->form .= "</fieldset>
                    <div class='errorInfo-".$fieldName."'></div>";
        return $this;
    }

    /**
     * Fonction pour créer un input type submit
     * @param string $fieldName
     * @param array $attributes
     * retourne la propriété form de la classe modifiée
     */
    public function submit(string $label=null, array $attributes=null){
        $this->form .= "<input type='submit' name='lead_submit' value='".$this->cleanStr($label)."' ";
        if(isset($attributes)) $this->form .= $this->addAttributes($attributes);
        $this->form .= "/>";
        return $this;
    }

    //fonction pour ajouter du contenu HTML dans le form (cf form parrainage)
    public function addHTMLcontent($HTMLcontent){
        // trancher si htmlcontent ou textcontent
        if(isset($HTMLcontent)) $this->form .= $HTMLcontent;
        return $this;
    }

    //fonction pour initialiser le formulaire
    public function openForm($formClassParam = null) {
        global $session_id, $idClient, $tabData, $row;
        $urlConnector = '' ;
        // récupération des valeurs 
        $uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
        isset($formClassParam) ? $this->formClass = $formClassParam : '';
        //$urlConnector = '/2_dev/testValeurs.php/ ';
        $this->form = "<form method='post' action='".$urlConnector."' name='form' id='form' class='".$this->formClass."'>";
        return $this;
    }

    /**
    * Fonction pour cloturer le formulaire et l'afficher
    * 2 paramètres à la fonction pour afficher idEnseigneInput
    * @param boolean $idEnseigneInput
    */
    public function closeForm($idEnseigneInput=true) {
        global $session_id, $tabData;
        $uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
        if ($uri == '/') {
            $uri = 'default';
        }
        $tracking1 = $_GET['tracking1'] ?? '';
        $tracking2 = $_GET['tracking2'] ?? '';
        $tracking3 = $_GET['tracking3'] ?? '';
        $ref = $_GET['ref'] ?? '';
        $idEnseigneInput = $_GET['idEnseigneInput'] ?? '';

        $this->form  .= "
                        <input type='hidden' name='lead_uri' value='".$uri."'/>
                        <input type='hidden' name='lead_session' value='".$session_id."'/>
                        <input type='hidden' name='tracking1' value='".$tracking1."'/>
                        <input type='hidden' name='tracking2' value='".$tracking2."'/>
                        <input type='hidden' name='tracking3' value='".$tracking3."'/>
                        <input type='hidden' name='ref' value='".$ref."'/>";
        $idEnseigneInput ? $this->form  .= "
                        <input type='hidden' name='idEnseigneInput' value='".$idEnseigneInput."'/>" : "";
        $this->form  .= "</form>";
        echo $this->form;
    }
}
?> 
    <main class="container mt-4 p-5" id="helperForm">
        <h1 class="text-warning fw-bolder fs-1 m-auto mb-4">Création d'un helper form</h1>
        <h2 class="text-light bg-dark fs-4 px-2">Context:</h2>
        <p>Un service interne à l'entreprise générait des landing pages pour l'inscription de prospect, le designer/intégrateur de ce service ne devait pas intervenir sur les fichiers PHP ou Javascript. La génération du formulaire était réalisée via une fonction php, cependant en raison des champs spécifiques voulus pour différentes campagnes marketing, la fonction s'était vue dupliquée en 4 exemplaires avec dans chaque exemplaire de 8 paramètres pour afficher des champs différents en fonction des campagnes. expl de la fonction et des paramtères : <span class="text-light bg-dark px-2 py-1">form_newTemplateUpdate('', 'Je prends rendez-vous', '', 'cp', '', '', 'client',TRUE );</span></p>
        <p>Le helper avait pour but de remplacer cette fonction pour gagner en visibilité et donne également plus d'autonomie au service en lui permettant de créer simplement des formulaires personnalisés sans intervenir coté back.</p>
        <p>C'est le dernier projet que j'ai pu mener avant mon alternance.</p>

        <section class="section__formulaire border border-5 border-warning rounded p-4 mb-5">
            <h3 class="form-title fw-bolder"><strong>Formulaire de contact</strong></h3>
                <?php //form_newTemplateUpdate('', 'Je prends rendez-vous', '', 'cp', '', '', 'client',TRUE ); ?>
                <?php
                $myForm = new CreateForm() ; 
                    $myForm->openForm()
                        ->input("text", "nom_prenom",["placeholder"=> "Prénom et Nom du client", "minlength"=>"2", "maxlength"=>"100", "class"=>"input", "required"=>"true", "autocomplete"=>"on"])
                        ->input("text", "code_postal",["placeholder"=> "Code postal du client", "maxlength"=>"5", "class"=>"input", "required"=>"true", "autocomplete"=>"on"])
                        ->input("tel", "telephone", ["placeholder"=> "Numéro de mobile du client", "maxlength"=>"10", "class"=>"input", "required"=>"true", "autocomplete"=>"on"])
                        ->input("mail", "email",["placeholder"=>"Votre email*"])
                        ->input("text", "numero_client", ["placeholder"=> "Numéro client", "maxlength"=>"9", "class"=>"input", "required"=>"true"])
                        ->input("text", "adresse", ["placeholder"=> "Votre adresse", "minlength"=> "2", "maxlength"=>"150", "class"=>"input", "required"=>"true", "autocomplete"=>"on"])
                        ->input("text","ville",["placeholder"=> "Votre ville", "maxlength"=>"150", "class"=>"input", "required"=>"true", "autocomplete"=>"on"])
                        ->select("horaires", ["class"=>"form-select"],["0"=> "- Votre plage de rappel -", "09h00-10h00"=>"09h00 - 10h00", "10h00-11h00"=>"10h00 - 11h00", "11h00-12h00"=>"11h00 - 12h00", "12h00-13h00"=>"12h00 - 13h00","13h00-14h00"=>"13h00 - 14h00", "14h00-15h00"=>"14h00 - 15h00", "15h00-16h00"=>"15h00 - 16h00", "16h00-17h00"=>"16h00 - 17h00","17h00-18h00"=>"17h00 - 18h00","18h00-19h00" =>"18h00 - 19h00"])
                        ->checkbox("contratX", "Etes-vous titulaire d'un contrat Enseigne X", null)
                        ->addHTMLcontent("<div class='p-3 mb-2 bg-warning text-white'>Possibilité d'ajouter du contenu HTML au form pour augmenter la personnalisation</div>")
                        ->submit('Je prends rendez-vous', ["class" =>"submit"])
                        ->closeForm();
                ?>
        </section>
    </main>