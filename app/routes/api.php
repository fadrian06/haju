<?php

use App\Models\ConsultationCauseCategory;
use MegaCreativo\API\CedulaVE;

App::group('/api', function (): void {
  $categoryMapper = new class {
    function __invoke(ConsultationCauseCategory $category): array {
      return [
        'id' => $category->id,
        'name' => [
          'short' => $category->shortName,
          'extended' => $category->extendedName
        ],
        'parentCategory' => $category->parentCategory
          ? ($this)($category->parentCategory)
          : null
      ];
    }
  };

  App::route('/causas-consulta/categorias', function () use ($categoryMapper): void {
    $categories = App::consultationCauseCategoryRepository()->getAll();

    App::json(array_map([$categoryMapper, '__invoke'], $categories));
  });

  App::route('/causas-consulta', function () use ($categoryMapper): void {
    $causes = App::consultationCauseRepository()->getAllWithGenerator();

    $json = [];

    foreach ($causes as $cause) {
      $json[] = [
        'id' => $cause->id,
        'name' => [
          'short' => $cause->getFullName(),
          'extended' => $cause->getFullName(abbreviated: false)
        ],
        'code' => $cause->code,
        'category' => $categoryMapper($cause->category)
      ];
    }

    App::json($json);
  });

  App::route('/causas-consulta/@id', function (int $id) use ($categoryMapper): void {
    $cause = App::consultationCauseRepository()->getById($id);

    App::json([
      'id' => $cause->id,
      'name' => [
        'short' => $cause->getFullName(),
        'extended' => $cause->getFullName(abbreviated: false)
      ],
      'code' => $cause->code,
      'category' => $categoryMapper($cause->category)
    ]);
  });

  App::route('/cedulacion/@idCard', function (int $idCard): void {
    App::json(@CedulaVE::get('V', $idCard));
  });
});
