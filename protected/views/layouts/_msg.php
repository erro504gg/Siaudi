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
<!-- mensagems-->
<div id="mensagens">
    <?php if (Yii::app()->user->hasFlash('successo')): ?>
        <div class="sucesso">
            <p><?php echo Yii::app()->user->getFlash('successo'); ?></p>
        </div>
    <?php endif; ?>
    <?php if (Yii::app()->user->hasFlash('erro')): ?>
        <div class="erro">
            <p><?php echo Yii::app()->user->getFlash('erro'); ?></p>
        </div>
    <?php endif; ?>
    <?php if (Yii::app()->user->hasFlash('orientacao')): ?>
        <div class="orientacao">
            <p><?php echo Yii::app()->user->getFlash('orientacao'); ?></p>
        </div>
    <?php endif; ?>

    <?php if (Yii::app()->user->hasFlash('aviso')): ?>
        <div class="alerta">
            <p><?php echo Yii::app()->user->getFlash('aviso'); ?></p>
        </div>
    <?php endif; ?>

    <?php
    Yii::app()->clientScript->registerScript(
            'escondeMsg',
            '$(".sucesso").animate({opacity: 1.0}, 3000).fadeOut("slow");
             $(".alerta").animate({opacity: 1.0}, 5000).fadeOut("slow");'
            , CClientScript::POS_READY
    );
    ?>
</div>
