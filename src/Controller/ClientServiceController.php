<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\ClientService;
use App\Form\ClientServiceType;
use App\Repository\ClientServiceRepository;
use App\Repository\ProductRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;

/**
 * @Route("/api/services")
 */
class ClientServiceController extends AbstractFOSRestController
{

    /**
     * @param ClientServiceRepository $serviceRepository
     * @param ProductRepository $productRepository
     */
    public function __construct(
        private ClientServiceRepository $serviceRepository,
        private ProductRepository       $productRepository
    )
    {
    }

    /**
     * @Route("/add", "name=services_add", methods={"POST"})
     * @OA\Post (description="Добавление услуги")
     * @OA\RequestBody(
     *     @OA\JsonContent(
     *     @OA\Property(type="string", property="name", required={"true"}),
     *     @OA\Property(type="string", format="uuid", property="product", required={"true"})
     * ))
     * @OA\Response(response="200", description="successful operation",
     *     @OA\JsonContent(@OA\Schema ( @OA\Property(property="item", ref=@Model(type=ClientService::class))
     * )))
     * @OA\Response(response="400", description="Validation Failed")
     * @param Request $request
     * @return View
     */
    public function add(Request $request): View
    {
        $service = new ClientService();

        $form = $this->createForm(ClientServiceType::class, $service);
        $form->submit($request->request->all());

        $view = $this->view($form);

        if ($form->isValid()) {
            $this->serviceRepository->save($service, true);

            $view->setData(['item' => $this->serviceRepository->find($service->getId())]);
        }

        return $view;
    }

    /**
     * @Route("/edit/{id}", "name=client_services_edit", methods={"POST"})
     * @OA\Post (description="Редактирование услуги")
     * @OA\RequestBody(
     *     @OA\JsonContent(
     *     @OA\Property(type="string", property="name", required={"true"}),
     *     @OA\Property(type="string", format="uuid", property="product", required={"true"})
     * ))
     * @OA\Response(response="200", description="successful operation",
     *     @OA\JsonContent(@OA\Schema ( @OA\Property(property="item", ref=@Model(type=ClientService::class))
     * )))
     * @OA\Response(response="400", description="Error validation: impossible to change product ID")
     * @param $id
     * @param Request $request
     * @return View
     */
    public function edit($id, Request $request): View
    {
        $service = $this->serviceRepository->find($id);

        if (!$service) {
            throw $this->createNotFoundException();
        }

        $product = $service->getProduct();

        $form = $this->createForm(ClientServiceType::class, $service);
        $form->submit($request->toArray(), false);

        $view = $this->view($form);

        if ($product->getId() !== $service->getProduct()->getId()) {
            throw new HttpException(400, 'Error validation: impossible to change product ID');
        }

        if ($form->isValid()) {
            $service->setProduct($product);
            $this->serviceRepository->save($service, true);

            $view->setData(['item' => $this->serviceRepository->find($service->getId())]);
        }

        return $view;

    }

    /**
     * @Route("/", "name=client_services_items", methods={"GET"})
     * @OA\Get (description="Список существующих услуг клиента")
     * @OA\Response(response="200", description="successful operation",
     *     @OA\JsonContent(@OA\Schema ( @OA\Property(property="item", ref=@Model(type=ClientService::class))
     * )))
     * @return View
     */
    public function items(): View
    {
        $items = $this->serviceRepository->findAll();

        return $this->view(['items' => $items]);
    }

    /**
     * @Route("/delete/{id}", "name=client_services_delete", methods={"GET"})
     * @OA\Get (description="Удалить услугу")
     * @OA\Response(response="204", description="successful operation")
     * @param $id
     * @return View
     */
    public function delete($id): View
    {
        $service = $this->serviceRepository->find($id);

        if (!$service) {
            throw $this->createNotFoundException();
        }

        $this->serviceRepository->remove($service, true);

        return $this->view([], Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/upgrade/{id}/{product_id}", "name=client_services_upgrade_product", methods={"POST"})
     * @OA\Post (description="Смена тарифа (upgrade)")
     * @OA\Response(response="200", description="successful operation",
     *     @OA\JsonContent(@OA\Schema ( @OA\Property(property="item", ref=@Model(type=ClientService::class))
     * )))
     * @param $id
     * @param Request $request
     * @return View
     */
    public function upgrade($id, Request $request): View
    {
        $service = $this->serviceRepository->find($id);

        $product = $this->productRepository->find($request->attributes->get('product_id'));

        if (!($service && $product)) {
            throw $this->createNotFoundException();
        }

        //TODO проверка правильности направления движения по тарифной линейке

        $service->setProduct($product);

        $this->serviceRepository->save($service, true);

        return $this->view(['item' => $this->serviceRepository->find($service->getId())]);
    }

    /**
     * @Route("/downgrade/{id}/{product_id}", "name=client_services_downgrade_product", methods={"POST"})
     * @OA\Post (description="Смена тарифа (downgrade)")
     * @OA\Response(response="200", description="successful operation",
     *     @OA\JsonContent(@OA\Schema ( @OA\Property(property="item", ref=@Model(type=ClientService::class))
     * )))
     * @OA\Response(response="400", description="Impossible to make downgrade in automatic mode")
     * @param $id
     * @param Request $request
     * @return View
     */
    public function downgrade($id, Request $request): View
    {
        $service = $this->serviceRepository->find($id);

        $product = $this->productRepository->find($request->attributes->get('product_id'));

        if (!($service && $product)) {
            throw $this->createNotFoundException();
        }

        $currentProduct = $this->productRepository->find($service->getProduct());

        //TODO проверка правильности направления движения по тарифной линейке

        if ($product->getDiskSizeGb() !== $currentProduct->getDiskSizeGb()) {
            throw new HttpException(400, 'Impossible to make downgrade in automatic mode');
        }

        $service->setProduct($product);

        $this->serviceRepository->save($service, true);

        return $this->view(['item' => $this->serviceRepository->find($service->getId())]);

    }
}