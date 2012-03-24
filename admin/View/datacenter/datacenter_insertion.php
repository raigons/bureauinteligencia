<div class="form-insert">
    <h2>Insira a planilha com os dados no formulário</h2>
    <form action="<?echo LinkController::getBaseURL()?>/admin/datacenter/insert" method="post" id="form-datacenter" enctype="multipart/form-data">
        <fieldset>
            <div class="field" style="text-align: center">
                <label>Grupo</label>
                <select id="groups" type="select-one">
                    <option value=""></option>
                </select>
            </div>
            <div class="field" style="text-align: center">
                <label>Subgrupo</label>
                <select id="subgroups" type="select-one" disabled="disabled">
                    <option value=""></option>
                </select>
                <label>Tipo</label>
                <select id="coffetype" type="select-one">
                    <option value=""></option>
                </select>
                <label>Variedade</label>
                <select id="variety" type="select-one">
                    <option value=""></option>
                </select>
                <label>Origem</label>
                <select id="origin" type="select-one">
                    <option value=""></option>
                </select>
                <label>Destino</label>
                <select id="destiny" type="select-one">
                    <option value=""></option>
                </select>
                <label>Fonte</label>
                <select id="font" type="select-one" disabled="disabled">
                    <option value=""></option>
                </select>
            </div>
            <div class="country_radios" style="display: none; margin: 30px 0 10px;">
                <strong>Selecione o grupo de países que os dados da planilha pertence:</strong><br /><br />
                <input id="country_group_1" type="radio" class="country_group" name="country_group" value="destiny" /> 
                <label for="country_group_1">EUA, França, Alemanha, Canadá, Itália, Japão, etc.</label>
                <br />
                <input id="country_group_2" type="radio" class="country_group" name="country_group" value="origin" /> 
                <label for="country_group_2">Brasil, Colômbia, Vietnã, Guatemala, Peru, Quênia</label>
                <br /><br />
            </div>
            <div class="field">
                <label>Planilha .xls</label>
                    <input type="file" id="datacenter-spreadsheet" name="Planilha"/><div class="erro"></div>
            </div>
            <button type="submit" class="button-insert-spreadsheet">Inserir</button>
        </fieldset>
    </form>
</div>