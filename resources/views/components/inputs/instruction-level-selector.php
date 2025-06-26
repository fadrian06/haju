<?php



use flight\util\Collection;
use HAJU\InstructionLevels\Domain\InstructionLevel;

/**
 * @var InstructionLevel[] $instructionLevels
 * @var Collection $lastData
 */

?>

<?php Flight::render('components/inputs/select', [
  'name' => 'instruction_level_id',
  'options' => array_map(static fn(InstructionLevel $instructionLevel): array => [
    'slot' => $instructionLevel->getName(),
    'value' => $instructionLevel->id,
    'selected' => $instructionLevel->id === ($lastData['instruction_level_id'] ?? ''),
  ], $instructionLevels),
  'label' => 'Nivel de instrucción',
]);
