## FILES module

Модуль работы с файлами. Сохраняет файлы локально в директорию */web/files* или удаленно по урлу.

В базу сохраняются:
 - полный путь **full_path** (от корня сервера для консольной задачи, в остальных случаях урл);
 - относительный путь **path**
 - изначальное имя файла **title**
 - сгенерированное название файла с расширением **filename**
 - Mime-тип **mimetype**
 - Хэш от файла **hash**
 - Размер в байтах **size**
 - Тип **type** (локальный или нет)
 
```php
// сохранение файла из UploadedFile
$this->filesService->saveUploadedFile(UploadedFile $file): Files;

// сохранение массива файлов UploadedFile
$this->filesService->saveUploadedFile(UploadedFile[] $files): Files[];

// сохранение файла из Base64
$this->filesService->saveBase64File(string $base64): Files;

// удаление файла по id
$this->fiilesRepository->removeById(int $id): bool;

// удаление файла по объекту Files
$this->fiilesRepository->remove(Files $file): bool;
```

Пара примеров есть в **commands/FilesTestController.php** 

-------------------------------------------------------------

*//@TODO: проверить сохранение из UploadedFile*

*//@TODO: реализовать и проверить сохранение файлов в cdn*
