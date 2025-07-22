<?php

namespace App\Controller;

use App\Entity\Region;
use App\Entity\Province;
use App\Entity\City;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LocationController extends AbstractController
{
    /**
     * @Route("/import-locations", name="import_locations")
     */
    public function importLocations(EntityManagerInterface $em): Response
    {
        $filePath = "E:/log-monitoring-tool/PSGC-2Q-2025-Publication-Datafile.csv";
        if (!file_exists($filePath)) {
            return new Response("File not found.");
        }

        $regionCount = $this->importRegions($filePath, $em);
        $provinceCount = $this->importProvinces($filePath, $em);
        $cityCount = $this->importCities($filePath, $em);

        $total = $regionCount + $provinceCount + $cityCount;

        return new Response("Imported $total records: $regionCount regions, $provinceCount provinces, $cityCount cities.");
    }

    private function importRegions(string $filePath, EntityManagerInterface $em): int
    {
        $count = 0;
        if (($handle = fopen($filePath, 'r')) !== false) {
            fgetcsv($handle); // Skip header
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                if (count($data) < 12) continue;
                [$code, $description,, $level] = array_map('trim', $data);
                if (strtolower($level) !== 'reg') continue;

                $region = $em->getRepository(Region::class)->findOneBy(['description' => $description]);
                if (!$region) {
                    $region = new Region();
                    $region->setCode($code);
                    $region->setDescription($description);
                    $em->persist($region);
                    $count++;
                }
            }
            fclose($handle);
            $em->flush();
        }
        return $count;
    }

    private function importProvinces(string $filePath, EntityManagerInterface $em): int
    {
        $count = 0;
        if (($handle = fopen($filePath, 'r')) !== false) {
            fgetcsv($handle); // Skip header
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                if (count($data) < 12) continue;
                [$code, $description,, $level,,,,,,, $regionName] = array_map('trim', $data);
                if (strtolower($level) !== 'prov') continue;

                $regionCode = substr($code, 0, 2) . '00000000';
                $region = $em->getRepository(Region::class)->findOneBy(['code' => $regionCode]);
                if (!$region) continue;

                $province = $em->getRepository(Province::class)->findOneBy(['description' => $description]);
                if (!$province) {
                    $province = new Province();
                    $province->setCode($code);
                    $province->setDescription($description);
                    $province->setRegion($region);
                    $em->persist($province);
                    $count++;
                }
            }
            fclose($handle);
            $em->flush();
        }
        return $count;
    }



    private function importCities(string $filePath, EntityManagerInterface $em): int
    {
        $count = 0;
        if (($handle = fopen($filePath, 'r')) !== false) {
            fgetcsv($handle); // Skip header
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                if (count($data) < 12) continue;
                [$code, $description,, $level] = array_map('trim', $data);

                $level = strtolower($level);
                if (!in_array($level, ['city', 'mun', 'huc', 'icc'])) continue;

                $provinceCode = substr($code, 0, 5) . '00000';
                $province = $em->getRepository(Province::class)->findOneBy(['code' => $provinceCode]);
                if (!$province) continue;

                $existingCity = $em->getRepository(City::class)->findOneBy(['description' => $description]);
                if (!$existingCity) {
                    $city = new City();
                    $city->setCode($code);
                    $city->setDescription($description);
                    $city->setProvince($province);
                    $city->setIsMunicipality($level === 'mun');
                    $em->persist($city);
                    $count++;
                }
            }
            fclose($handle);
            $em->flush();
        }
        return $count;
    }
}
