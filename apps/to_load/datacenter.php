<div id="datacenter" class="app">
    <h1>
        Data Center 
        <span>
            <a href="#" class="lnk-minimizar">&nbsp;---&nbsp;</a> 
            <a href="#" class="lnk-remover">&nbsp;X&nbsp;</a>
        </span>
    </h1>
    <div class="app-conteudo">
        <!--<p>Conte&uacute;do do aplicativo Datacenter; apenas o conteudo desta div via aparecer</p>-->
        <div class="app-content-body">
            <div id="show-datacenter">
                <?if(Session::isLogged()):?>
                <img style="position: relative; top: 5px" src="<?echo LinkController::getBaseURL()?>/images/statistics_icon.png"/>
                <a class="link-datacenter" href="<?echo LinkController::getBaseURL()?>/estatisticas_cafeeiras" target="_blank">                    
                    Estatísticas Cafeeiras
                </a>
                <?else:?>
                <div class="must-do-login">
                    Faça o login no sistema para ter acesso ao Datacenter.<br />
                    Se ainda não possui um cadastro,  
                    <a href="<?echo LinkController::getBaseURL()?>/cadastro">Clique aqui</a>.                    
                </div>
                <?endif?>
            </div>
        </div>
    </div>
</div>
