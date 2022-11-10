<?php // розширення для твіг бібліотеки

namespace Blog\Twig; // загалом це розширення викликає getFunctions і створює нову функцію (обробника для asset_url)

use Psr\Http\Message\ServerRequestInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AssetExtension extends AbstractExtension // АбстрактЕкстеншин це розширення твігу для роботи з ЮРЛ
{
    private ServerRequestInterface $request;

    public function __construct(ServerRequestInterface $request)
    {
        $this->request = $request;
    }

    public function getFunctions() // викликає функцію, яка обробляє asset_url, url, base_url
   {
       return [
           new TwigFunction('asset_url', [$this, 'getAssetUrl']), // фікс вивода картинок на блог 1/2/3
           new TwigFunction('url', [$this, 'getUrl']), // зміна навігації
           new TwigFunction('base_url', [$this, 'getBaseUrl']),

       ];
   }

   public function getAssetUrl(string $path): string
   {
       return $this->getBaseUrl() . $path;
   }

   public function getBaseUrl(): string
   {
       $params = $this->request->getServerParams();  // функція виводу картинок на блог 1/2/3
       $scheme = $params['REQUEST_SCHEME'] ?? 'http';
       return  $scheme . '://' . $params['HTTP_HOST'] . '/';
   }

   public function getUrl(string $path): string
   {
       return $this->getBaseUrl() . $path;
   }
}