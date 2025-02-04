<?php
/**
 * This is the template for generating a controller class file for CRUD feature.
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php echo "<?php\n"; ?>

class <?php echo $this->controllerClass; ?> extends <?php echo $this->baseControllerClass; ?> {

    public   $titulo = '<?php echo $this->modelClass; ?>';

  public function init() {
        parent::init();
        $this->menu_acao = array(
            array('label' => 'Consultar', 'url' => array('<?php echo $this->modelClass; ?>/index')),
            array('label' => 'Incluir', 'url' => array('<?php echo $this->modelClass; ?>/admin')),
        );
    }

<?php
	$authpath = 'ext.giix-core.giixCrud.templates.default.auth.';
	Yii::app()->controller->renderPartial($authpath . $this->authtype);
?>
    public function actionAdmin($id = 0) {

        if (!empty($id)) {
            $this->subtitulo = 'Atualizar';
            $model = $this->loadModel($id , '<?php echo $this->modelClass; ?>');
        } else {
            $this->subtitulo = 'Inserir';
            $model = new <?php echo $this->modelClass; ?>;
        }
        <?php if ($this->enable_ajax_validation): ?>
		$this->performAjaxValidation($model, '<?php echo $this->class2id($this->modelClass)?>-form');
        <?php endif; ?>

        if (isset($_POST['<?php echo $this->modelClass; ?>'])) {
            $model->attributes = $_POST['<?php echo $this->modelClass; ?>'];
          <?php if ($this->hasManyManyRelation($this->modelClass)): ?>
            $relatedData = <?php echo $this->generateGetPostRelatedData($this->modelClass, 4); ?>;
          <?php endif; ?>

          <?php if ($this->hasManyManyRelation($this->modelClass)): ?>
		if ($model->saveWithRelated($relatedData)) {
          <?php else: ?>
		if ($model->save()) {
          <?php endif; ?>
                    $this->setFlashSuccesso( ($id > 0 ? 'alterar' : 'inserir') );
                    $this->redirect(array('index?' . $_SERVER['QUERY_STRING']));
            }
        }

        $this->render('admin', array(
            'model' => $model,
            'titulo' => $this->titulo,
        ));
    }

    public function actionDelete($id) {
        $this->layout = false;
        if (Yii::app()->getRequest()->getIsPostRequest()) {
            $this->loadModel($id, '<?php echo $this->modelClass; ?>')->delete();

             if (Yii::app()->getRequest()->getIsAjaxRequest()) {
                $this->setFlashSuccesso('excluir');
                echo  $this->getMsgSucessoHtml();
            } else {
                $this->setFlashError('excluir');
                $this->redirect(array('admin'));
            }
            
        } else
            throw new CHttpException(400, Yii::t('app', 'Sua requisição é inválida.'));
    }

    public function actionIndex() {
        $this->subtitulo = 'Consultar';
        $dados = null;
        $model = new <?php echo $this->modelClass; ?>('search');

        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['<?php echo $this->modelClass; ?>'])) {
            $model->attributes = $_GET['<?php echo $this->modelClass; ?>'];
            $dados = $model->search();
        }

        $this->render('index', array(
            'model' => $model,
            'dados' => $dados,
            'titulo' => $this->titulo,
        ));
    }

    public function actionView($id) {
        $this->layout = false;
        $this->render('view', array(
                'model' => $this->loadModel($id, '<?php echo $this->modelClass; ?>'),
        ));
    }
}
