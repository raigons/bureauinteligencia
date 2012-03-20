<div class="form-insert">
    <h2>Inserção de país</h2>
    <form title="country" action="<?echo LinkController::getBaseURL()?>/admin/datacenter/country" method="post" id="form-country">
        <fieldset>
            <div class="field">
                <label>Nome do País:</label>
                <input type="text" value="" id="name" />
            </div>
            <div class="field">
                <label>Tipo do país:</label>
                <select id="type_country" type="select-one">
                    <option value=""></option>
                    <option value="origin">Origem</option>
                    <option value="destiny">Destino</option>
                </select>                
            </div>
            <div class="field">
                <label for="reexport">Reexportação:</label>
                <input id="reexport" type="checkbox" name="reexport" value="true"/>
            </div>
            <button type="submit" class="button-insert">Inserir</button>
        </fieldset>
    </form>
</div>