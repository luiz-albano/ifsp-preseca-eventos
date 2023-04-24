<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Yaml\Yaml;

class DashboardMenuBuilderController extends AbstractController
{

    /**
     * Renderiza o menu de navegação contido na sidebar do ambiente restrito (dashboard)
     * 
     * @access public
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function renderSidebar()
    {
        $resource = $this->getParameter('kernel.project_dir') . "/config/dashboard.yaml";   
        $configValues = Yaml::parse(file_get_contents($resource));

        return $this->render(
            '@adminkit/sidebar.html.twig',
            [ 'config' => $configValues ]
        );
    }
}