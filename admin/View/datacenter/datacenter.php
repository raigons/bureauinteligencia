<?  
require_once '../core/Datacenter/CountryMap.php';
require_once '../core/generics/GenericDao.php';
require_once '../core/generics/Param.php';
require_once '../core/generics/datacenter/Country.php';
require_once '../core/generics/Controller.php';
//require_once '../core/Datacenter/cache/cache_countries.php'; 
?>
<h2>
    Administrador do Datacenter
</h2>
<h3>
    <?CacheCountry::setCacheBehavior(SessionAdmin::getCacheBehavior());?>
    <?CacheCountry::cacheCountries();?>
    <?print_r(CacheCountry::getCountries());?>
</h3>

<div class="inside-item-admin">
    <ul>
        <li>
            <a href="<?echo $baseUrl?>/admin/datacenter/paises">Inserir País</a>
        </li>
        <li>
            <a href="<?echo $baseUrl?>/admin/datacenter/paises/origem">Listar Países de Origem</a>
        </li>
        <li>
            <a href="<?echo $baseUrl?>/admin/datacenter/paises/destino">Listar Países de Destino</a>
        </li>
        <li>
            <a href="<?echo $baseUrl?>/admin/datacenter/list">Listar Dados</a>
        </li>
        <li>
            <a href="<?echo $baseUrl?>/admin/datacenter/inserir">Inserir Dados</a>
        </li>
    </ul>
</div>