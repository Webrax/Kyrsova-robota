<?php // розширення для твіг бібліотеки

namespace Blog\Twig; // загалом це розширення викликає getFunctions і створює нову функцію (обробника для asset_url)

use Psr\Http\Message\ServerRequestInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AssetExtension extends AbstractExtension
{
    private ServerRequestInterface $request;

    public function __construct(ServerRequestInterface $request)
    {
        $this->request = $request;
    }

    public function getFunctions()
   {
       return [
           new TwigFunction('asset_url', [$this, 'getAssetUrl']), // фікс вивода картинок на блог/1/2/3
           new TwigFunction('url', [$this, 'getUrl']), // фікс вивода картинок на блог/1/2/3
           new TwigFunction('base_url', [$this, 'getBaseUrl']), // фікс вивода картинок на блог/1/2/3

       ];
   }

   public function getAssetUrl(string $path): string
   {
       return $this->getBaseUrl() . $path;
   }

   public function getBaseUrl(): string
   {
       $params = $this->request->getServerParams();
       return 'http' . '://' . $params['HTTP_HOST'] . '/';
   }

   public function getUrl(string $path): string
   {
       return $this->getBaseUrl() . $path;
   }
}