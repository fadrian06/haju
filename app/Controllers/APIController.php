<?php

declare(strict_types=1);

namespace HAJU\Controllers;

use App\Models\ConsultationCause;
use App\Models\ConsultationCauseCategory;
use App\Models\Patient;
use Flight;
use HAJU\Enums\Theme;
use Leaf\Http\Session;

final readonly class APIController {
  private function __construct() {
  }

  public static function healthCheck(): void {
    Flight::json(['status' => 'ok']);
  }

  public static function setTheme(string $theme): void {
    $parsedTheme = Theme::from($theme);
    Session::set('theme', $parsedTheme->value);
  }

  public static function checkSecretKey(): void {
    $secretKey = Flight::request()->data['secret_key'];

    if (strval($secretKey) !== strval($_ENV['SECRET_KEY'])) {
      Flight::json('Clave maestra incorrecta', 401);

      return;
    }

    Session::set('let_register_director', true);
  }

  public static function showAllConsultationCauseCategories(): void {
    $categories = ConsultationCauseCategory::with('parentCategory')
      ->whereNot('top_category_id', null)
      ->get();

    Flight::json($categories);
  }

  public static function showConsultationCauseCategoryDetails(int $id): void {
    $relations = ['parentCategory', 'causes'];
    $category = ConsultationCauseCategory::with($relations)->findOrFail($id);

    Flight::json($category);
  }

  public static function showAllConsultationCauses(): void {
    $causes = ConsultationCause::with(['category'])->get();

    Flight::json($causes);
  }

  public static function showConsultationCauseDetails(int $id): void {
    $cause = ConsultationCause::with(['category'])->findOrFail($id);

    Flight::json($cause);
  }

  public static function showPatientDetails(int $id): void {
    $patient = Patient::with('consultations')->findOrFail($id);

    Flight::json($patient);
  }
}
