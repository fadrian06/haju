<?php

use App\Models\ConsultationCause;
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
    $causes = App::consultationCauseRepository()->getAll();

    App::json(array_map(function (ConsultationCause $cause) use ($categoryMapper): array {
      return [
        'id' => $cause->id,
        'name' => $cause->getFullName(),
        'code' => $cause->code,
        'category' => $categoryMapper($cause->category)
      ];
    }, $causes));
  });

  App::route('/cedulacion/@idCard', function (int $idCard): void {
    App::json(@CedulaVE::get('V', $idCard));
  });
});
