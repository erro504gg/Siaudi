<?php
/********************************************************************************
*  Copyright 2015 Conab - Companhia Nacional de Abastecimento                   *
*                                                                               *
*  Este arquivo é parte do Sistema SIAUDI.                                      *
*                                                                               *
*  SIAUDI  é um software livre; você pode redistribui-lo e/ou                   *
*  modificá-lo sob os termos da Licença Pública Geral GNU conforme              *
*  publicada pela Free Software Foundation; tanto a versão 2 da                 *
*  Licença, como (a seu critério) qualquer versão posterior.                    *
*                                                                               *
*  SIAUDI é distribuído na expectativa de que seja útil,                        *
*  porém, SEM NENHUMA GARANTIA; nem mesmo a garantia implícita                  *
*  de COMERCIABILIDADE OU ADEQUAÇÃO A UMA FINALIDADE ESPECÍFICA.                *
*  Consulte a Licença Pública Geral do GNU para mais detalhes em português:     *
*  http://creativecommons.org/licenses/GPL/2.0/legalcode.pt                     *
*                                                                               *
*  Você deve ter recebido uma cópia da Licença Pública Geral do GNU             *
*  junto com este programa; se não, escreva para a Free Software                *
*  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA    *
*                                                                               *
*  Sistema   : SIAUDI - Sistema de Auditoria Interna                            *
*  Data      : 05/2015                                                          *
*                                                                               *
********************************************************************************/
?>
<?php

class Menu {

    // Método para gerar o menu apenas do sistema 
    // "Chamada Pública 001-2013
    public function gerarMenuChamadaPublica() {
        $perfil = strtolower(Yii::app()->user->role);
        //debug($_SERVER['SERVER_NAME']);
        //Yii::app()->baseUrl;
        // menu para gerenciar sistema
        /*
          if($perfil=="chamadapublica_gestao"){
          // Monta Menus de nível 0
          $rsNiv0=array( array('titulo'=> 'Consultas',
          'link' => '',
          'id'   => 'm_consultas'),
          array('titulo'=> 'Gestão',
          'link' => '',
          'id'   => 'm_gestao'),);


          // Monta Menus de nível 1
          $rsNiv1=array( array(  'aba' => 'm_consultas',
          'link' => '',
          'menu_pai_fk'=>'697',
          'titulo' => 'Consultar Projetos',
          'id2'  => 'm_consultar_projetos'),);


          // Monta Menus de nível 2
          $rsNiv2=array( array(  'aba' => 'm_consultar_projetos',
          'menu_pai_fk'=>'695',
          'link' =>  Yii::app()->request->baseUrl.'/Projetos',
          'titulo' => 'Consultar'),);
          }
         */
        // Menu atual para perfil  de gestão 
        if ($perfil == "chamadapublica_gestao") {
            // Monta Menus de nível 0
            $rsNiv0 = array(array('titulo' => 'Consultas',
                    'link' => '',
                    'id' => 'm_consultas'),
            );

            // Monta Menus de nível 1
            $rsNiv1 = array(array('aba' => 'm_consultas',
                    'link' => '',
                    'menu_pai_fk' => '697',
                    'titulo' => 'Consultar Projetos',
                    'id2' => 'm_consultar_projetos'),);


            // Monta Menus de nível 2
            $rsNiv2 = array(array('aba' => 'm_consultar_projetos',
                    'menu_pai_fk' => '695',
                    'link' => Yii::app()->request->baseUrl . '/Projeto',
                    'titulo' => 'Consultar'),);
        }

        // menu para consultar relatórios
        if ($perfil == "chamadapublica_geral") {
            // Monta Menus de nível 0
            $rsNiv0 = array(array('titulo' => 'Consultas',
                    'link' => '',
                    'id' => 'm_consultas'),);


            // Monta Menus de nível 1
            $rsNiv1 = array(array('aba' => 'm_consultas',
                    'link' => '',
                    'menu_pai_fk' => '697',
                    'titulo' => 'Consultar Projetos',
                    'id2' => 'm_consultar_projetos'),);


            // Monta Menus de nível 2
            $rsNiv2 = array(array('aba' => 'm_consultar_projetos',
                    'menu_pai_fk' => '695',
                    'link' => Yii::app()->request->baseUrl . '/Projeto',
                    'titulo' => 'Consultar'),);
        }

        //$menu = VwMenu::model()->findAll($criteria);
        $menuHtml = $this->montaMenu($rsNiv0, $rsNiv1, $rsNiv2);
        return $menuHtml;
    }

    public function gerarMenu() {

        $criteria = new CDbCriteria;
        $criteria->condition = 'nivel= 0 AND perfil_fk=' . Yii::app()->user->id_perfil;
        $criteria->order = 'ordem ASC';
        $menu0 = VwMenu::model()->findAll($criteria);
        $menus = $this->montaArrayMenu($menu0);
        $rsNiv0 = $menus[0];

        $criteria = new CDbCriteria;
        $criteria->condition = 'nivel= 1 AND perfil_fk=' . Yii::app()->user->id_perfil;
        $criteria->order = 'menu_pai_fk, ordem ASC';

        $menu1 = VwMenu::model()->findAll($criteria);
        $menus = $this->montaArrayMenu($menu1, $menu0);
        $rsNiv1 = $menus[1];

        $criteria = new CDbCriteria;
        $criteria->condition = 'nivel= 2 AND perfil_fk=' . Yii::app()->user->id_perfil;
        $criteria->order = 'menu_pai_fk, ordem ASC';
        $menu2 = VwMenu::model()->findAll($criteria);
        $menus = $this->montaArrayMenu($menu2, $menu1);
        $rsSNiv = $menus[2];

        //$menu = VwMenu::model()->findAll($criteria);

        if (defined('IS_LAYOUT_BOOTSTRAP') && IS_LAYOUT_BOOTSTRAP)
        	$menuHtml = $this->montaMenuBootstrap($rsNiv0, $rsNiv1, $rsSNiv);
        else
        	$menuHtml = $this->montaMenu($rsNiv0, $rsNiv1, $rsSNiv);

        return $menuHtml;
    }

    private function montaMenuBootstrap($rsNiv0, $rsNiv1, $rsSNiv) {

    	ob_start(); 

    	// fonte dos ícones do font-awesome
    	// http://fortawesome.github.io/Font-Awesome/icons/
    	?>

		<ul class="nav navbar-nav">
			<?php foreach ($rsNiv0 as $key => $item0) { ?>
			<li>
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">
					<i class="fa <?php echo $item0['imagem'] ?>"></i> <?php echo $item0['titulo'] ?> <span class="caret"></span>
				</a>
				<ul class="dropdown-menu" role="menu">
					<?php foreach ($rsNiv1 as $key => $item1) { ?>
						<?php if ($item0['id'] == $item1['aba']) { ?>
							<li><a href="#" style="font-weight: bold"> <?php echo $item1['titulo'] ?> </a></li>
							
							<?php foreach ($rsSNiv as $key => $item2) { ?>
							
								<?php if ($item1['id2'] == $item2['aba']) { ?>
									<li style="margin-left: 20px"><a href="<?php echo $item2['link'] ?>"> <i class="fa fa-caret-right"></i> <?php echo $item2['titulo'] ?> </a></li>
								<?php } ?>

							<?php } ?>
							
						<?php } ?>
					<?php } ?>
				</ul>
			</li>
			<?php } ?>
		</ul><?php

        return ob_get_clean();
    }

    private function montaMenu($rsNiv0, $rsNiv1, $rsSNiv) {
        $menu = "";
        $menu = "<div class='wrapperMenuPrincipal'>";
        $menu .= " <!-- Menu de Modulos -->\n";
        $menu .= " <div id='menuNiv1'>\n";
        $menu .= " 	<ul>\n";
        $menu .= "          <li><a href='" . Yii::app()->request->baseUrl . "/'>Início</a></li>\n";
        foreach ($rsNiv0 as $key => $item) {
            if ($item['link'] != "") {
                $menu .= "  <li><a href='{$item['link']}'>{$item['titulo']}</a></li>\n";
            } else {
                $menu .= "  <li id='" . $item['id'] . "'><a href='#'>{$item['titulo']}</a></li>\n";
            }
        }
        $menu .= " 	</ul>\n";
        $menu .= " </div>\n";
        $menu .= " <div id='menuNiv2'>\n";

        $itemPai = 0;

        foreach ($rsNiv1 as $key => $item) {
            if (($itemPai != $item['menu_pai_fk']) && ($key > 0)) {
                $menu .= " 	</ul>\n";
                $menu .= " 	</div>\n";
            }
            if ($itemPai != $item['menu_pai_fk']) {
                $menu .= " 	<div id='{$item['aba']}'>\n";
                $menu .= " 	<ul>\n";
                $itemPai = $item['menu_pai_fk'];
            }

            if ($item['link'] != "") {
                $menu .= " 		<li id='" . $item['id2'] . "'><a href='{$item['link']}'>{$item['titulo']}</a></li>\n";
            } else {
                $menu .= " 		<li id='" . $item['id2'] . "'>{$item['titulo']}</li>\n";
            }
        }
        $menu .= " 	</ul>\n";
        $menu .= " 	</div>\n";
        $menu .= " </div>\n";

        $menu .= " <div id='menuNiv3'>\n";
        $itemPai = 0;
        /*
          foreach ($rsSNiv as $key => $item) {
          if (($itemPai != $item['menu_pai_fk']) && ($key > 0)) {
          $menu .= " 	</ul>\n";
          $menu .= " 	</div>\n";
          }
          if ($itemPai != $item['menu_pai_fk']) {
          $menu .= " 	<div id='{$item['aba']}'>\n";
          $menu .= " 	<ul>\n";
          $itemPai = $item['menu_pai_fk'];
          }
          $menu .= " <li title='{$item['titulo']}'><a href='{$item['link']}'>{$item['titulo']}</a></li>\n";
          }
         */

        foreach ($rsSNiv as $key => $item) {
            if (($itemPai != $item['menu_pai_fk'] && ($key > 0))) {
                //$menu .= "     </ul>";
                $menu .= " </div>";
            }
            if ($qtd == 7) {
                $menu .= " </ul>";
                $qtd = 0;
            }
            if ($itemPai != $item['menu_pai_fk']) {
                $menu .= " <div id='{$item['aba']}'>";
                //$menu .= "     <ul>";
                $qtd = 0;
                $itemPai = $item['menu_pai_fk'];
            }
            if ($qtd == 0) {
                $menu .= " <ul>";
            }
            $qtd++;
            $link = $item['link'] . $item['parametro'];
            $menu .= " <li title='{$item['titulo']}'><a href='{$item['link']}'>{$item['titulo']}</a></li>\n";
        }






        $menu .= " 	</ul>\n";
        $menu .= " 	</div>\n";
        $menu .= " </div></div>\n";

        $menu .= "<iframe id='correcaoMenuIE'  src='javascript:false;' scrolling='no' frameborder='0'></iframe>";

        return $menu;
    }

    public function montaArrayMenu($menu, $menuAnt = array()) {
        $sistema = '';
        if(Yii::app()->params['id_aplicacao_menu']){
            $sistema = '/' . Yii::app()->params['id_aplicacao_menu']; 
        }
        
        $rsNiv0 = array();
        $rsNiv1 = array();
        $rsSNiv = array();

        $aba = '';
        foreach ($menu as $value) {

            $id_titulo = strtolower(CMask::formattourl($value->titulo));
            $link = $value->url ? $sistema . $value->url : $value->url;
            switch ($value->nivel) {
                case 0:
                    $rsNiv0[] = array(
                        'titulo' => $value->titulo,
                        'link' => $link,
                        'id' => 'm_' . $id_titulo,
                        'imagem' => $value->imagem,
                    );
                    break;

                case 1:
                    foreach ($menuAnt as $key => $value2) {
                        if ($value2->id == $value->menu_pai_fk && $value2->nivel == 0) {
                            $aba = 'm_' . strtolower(CMask::formattourl($value2->titulo));
                        }
                    }
                    $rsNiv1[] = array(
                        'menu_pai_fk' => $value->menu_pai_fk,
                        'aba' => array($value->url),
                        'titulo' => $value->titulo,
                        'aba' => $aba,
                        'link' => $link,
                        'id2' => 'm_' . $id_titulo . '_sub',
                    );
                    break;

                case 2:
                    foreach ($menuAnt as $key => $value2) {
                        if ($value2->id == $value->menu_pai_fk && $value2->nivel == 1) {
                            $id_titulo = strtolower(CMask::formattourl($value2->titulo));
                            $aba = 'm_' . $id_titulo . '_sub';
                        }
                    }
                    $rsSNiv[] = array(
                        'titulo' => $value->titulo,
                        'menu_pai_fk' => $value->menu_pai_fk,
                        'link' => $link,
                        'aba' => $aba,
                    );
                    break;
            }
        }

        return array($rsNiv0, $rsNiv1, $rsSNiv);
    }

}
