<?php

namespace App\Controller\Core;

use App\Helper\Filemanager;
use App\Helper\Util;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/filemanager")
 */
class FilemanagerController extends AbstractController
{
    protected string $ds;
    protected string $base_path;
    protected string $uploads_folder;
    protected string $uploads_path;

    protected Filesystem $filesystem;
    protected Filemanager $filemanager;
    protected Util $util;

    public function __construct()
    {
        $this->ds = DIRECTORY_SEPARATOR;
        $this->uploads_folder = 'uploads';
        $this->base_path = realpath(__DIR__ . $this->ds . '..' . $this->ds . '..' . $this->ds . '..' . $this->ds . 'public' . $this->ds);
        $this->uploads_path = $this->base_path . $this->ds . $this->uploads_folder;

        $this->filesystem = new Filesystem();
        $this->filemanager = new Filemanager();
        $this->util = new Util();
    }

    /**
     * @Route("/list", name="sd_admin_filemanager_list")
     */
    public function list(Request $request): JsonResponse
    {
        if (!$this->filesystem->exists($this->uploads_path)) {
            $this->filesystem->mkdir($this->uploads_path);
        }

        $path = $request->get('data');
        $path = str_replace('..', '', $path);

        $files = [];
        $folders = [];
        $this->filemanager->list_dir($this->uploads_path, $path, $files, $folders);
        $files = array_merge($folders, $files);

        foreach ($files as $k => $v) {
            $i = $this->filemanager->get_file_info($v['path'], [
                'name', 'size', 'date'
            ]);

            if ($v['folder']) {
                $files[$k] = [
                    'name' => $i['name'],
                    'sizes' => '',
                    'size' => '----',
                    'date' => date('Y-m-d H:i:s', $i['date']),
                    'folder' => true,
                    'link' => str_replace($this->uploads_path, '', $v['path'])
                ];
            } else {
                $file = $this->uploads_folder . str_replace($this->uploads_path, '', $v['path']);
                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                if (in_array($ext, ['gif', 'png', 'jpg', 'jpeg'])) {
                    $thumbnail = $this->util->thumbnail($this->base_path, $file, 50);
                } else {
                    $thumbnail = [
                        'thumb' => $ext,
                        'sizes' => ''
                    ];
                }

                $files[$k] = [
                    'name' => $i['name'],
                    'sizes' => $thumbnail['sizes'],
                    'size' => $this->filemanager->human_filesize($i['size']),
                    'date' => date('Y-m-d H:i:s', $i['date']),
                    'folder' => false,
                    'thumb' => $thumbnail['thumb'],
                    'link' => $file
                ];
            }
        }

        return new JsonResponse(['status' => 'OK', 'files' => $files]);
    }

    /**
     * @Route("/upload", name="sd_admin_filemanager_upload")
     */
    public function upload(Request $request): Response
    {
        $folder = $request->get('data');
        $folder = str_replace('..', '', $folder);
        $folder = (substr($folder,0,8) == ($this->uploads_folder . $this->ds)) ? substr($folder, 8) : $folder;
        $folder = ($folder != '') ? ($this->uploads_folder . $this->ds . $folder) : $this->uploads_folder;
        $folder = $this->base_path . $this->ds . $folder;

        $file = $request->files->get('file');

        if (in_array($_FILES['file']['type'], $this->util->allowedFiles())) {
            $extension = '.' . $file->getClientOriginalExtension();
            $name = str_replace($extension, '', $file->getClientOriginalName());
            $file_name = $this->util->url_slug($name) . strtolower($extension);
            if ($this->filesystem->exists($folder . $this->ds . $file_name)) {
                $file_name = $this->util->url_slug($name) . '--' . date('Y-m-d--H-i-s') . strtolower($extension);
            }
            $file->move($folder, $file_name);
            return new Response('ok');
        } else {
            return new Response('Archivo no permitido o corrupto - ' . $_FILES['file']['type'], 403);
        }
    }

    /**
     * @Route("/delete", name="sd_admin_filemanager_delete")
     */
    public function delete(Request $request): JsonResponse
    {
        $file = $request->get('data');
        $file = str_replace('..', '', $file);
        $file = (substr($file,0,8) == ($this->uploads_folder . $this->ds)) ? str_replace(($this->uploads_folder . $this->ds), '', $file) : $file;

        if ($file != '') {
            $file_orig = $this->uploads_path . $this->ds . $file;
            if ($this->filesystem->exists($file_orig)) {
                $this->filesystem->remove($file_orig);
                return $this->response(true, 'Archivo / carpeta eliminado');
            } else {
                return $this->response(false, 'Archivo / carpeta no existe');
            }
        }

        return $this->response(False, 'Acción denegada');
    }

    /**
     * @Route("/create-folder", name="sd_admin_filemanager_create_folder")
     */
    public function creteFolder(Request $request): JsonResponse
    {
        $path = $this->uploads_path . $this->ds;

        $getfolder = $request->get('data');
        $getfolder = str_replace('..', '', $getfolder);
        $getfolder = explode($this->ds, $getfolder);
        array_walk($getfolder, function (&$v, $k) { $v = $this->util->url_slug($v); });
        $folder = implode($this->ds, $getfolder);
        $folder = (substr($folder,0,8) == $this->uploads_folder . $this->ds) ? str_replace($this->uploads_folder . $this->ds, '', $folder) : $folder;

        if ($folder != '') {
            $target = $path . $folder;

            if ($this->filesystem->exists($target))
                return $this->response(false, "Carpeta '$folder' ya existe");

            $this->filesystem->mkdir($target, 0755);

            return $this->response(true, "Carpeta '$folder' ha sido creado");
        }

        return $this->response(false, 'Acción denegada');
    }

    public function response($status, $message): JsonResponse
    {
        return new JsonResponse([
            'status' => $status,
            'msg' => $message
        ]);
    }
}
