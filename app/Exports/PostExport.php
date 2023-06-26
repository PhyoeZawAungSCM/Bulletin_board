<?php

namespace App\Exports;

use App\Models\Posts;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Sheet;
use Maatwebsite\Excel\Concerns\WithStyles;

Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
	$sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
});
Sheet::macro('styleCellsCondition', function (Sheet $sheet, string $cellRange, array $conditionalStyles) {
	$sheet->getDelegate()->getStyle($cellRange)->setConditionalStyles($conditionalStyles);
});
class PostExport implements
	FromCollection,
	WithHeadings,
	ShouldAutoSize,
	WithStrictNullComparison,
	WithMapping,
	WithColumnFormatting,
	WithEvents,
	WithStyles
{
	public function headings(): array
	{
		return[
			'#',
			'title',
			'description',
			'status',
			'create_user_id',
			'update_user_id',
			'delete_user_id',
			'deleted_at',
			'created_at',
			'updated_at'
		];
	}

	public function map($post): array
	{
		return[
			$post->id,
			$post->title,
			$post->description,
			$post->status,
			$post->create_user_id,
			$post->updated_user_id,
			$post->deleted_user_id,
			$post->deleted_at,
			Date::dateTimeToExcel(Carbon::parse($post->created_at)),
			Date::dateTimeToExcel(Carbon::parse($post->updated_at)),
		];
	}

	public function styles(Worksheet $sheet): array
	{
		return [
			1       => [
				'font' => [
					'bold' => true,
				],
				'alignment' => [
					'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
				],
				'borders' => [
					'bottom' => [
						'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					],
				],
				'fill' => [
					'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
					'rotation'   => 90,
					'startColor' => [
						'argb' => 'FFA0A0A0',
					],
					'endColor' => [
						'argb' => 'FFFFFFFF',
					],
				],
			],
		];
	}

	public function RegisterEvents(): array
	{
		return[
			AfterSheet::class => function (AfterSheet $event) {
				$conditional2 = new \PhpOffice\PhpSpreadsheet\Style\Conditional();
				$conditional2->setConditionType(\PhpOffice\PhpSpreadsheet\Style\Conditional::CONDITION_CELLIS);
				$conditional2->setOperatorType(\PhpOffice\PhpSpreadsheet\Style\Conditional::OPERATOR_NOTEQUAL);
				$conditional2->addCondition(0);
				$conditional2->getStyle()->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_DARKRED);
				$conditional2->getStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
				$conditional2->getStyle()->getFill()->getEndColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);

				$conditionalStyles[] = $conditional2;

				$event->sheet->styleCellsCondition('G2:G9', $conditionalStyles);

				// $event->sheet->styleCells(
				// 	'A1:H1',
				// 	[
				// 		'font'=> [
				// 			'bold'=> true
				// 		],
				//         'alignment' => [

				//         ],
				// 		'fill' => [
				// 			'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
				// 			'rotation'   => 90,
				// 			'startColor' => [
				// 				'argb' => 'FFA0A0A0',
				// 			],
				// 			'endColor' => [
				// 				'argb' => 'FFFFFFFF',
				// 			],
				// 		]
				// 	]
				// );
			}
		];
	}

	public function columnFormats(): array
	{
		return [
			'H' => NumberFormat::FORMAT_DATE_DDMMYYYY,
			'I' => NumberFormat::FORMAT_DATE_DDMMYYYY,
			'J' => NumberFormat::FORMAT_DATE_DDMMYYYY,
		];
	}

	/**
	* @return \Illuminate\Support\Collection
	*/
	public function collection()
	{
		return Posts::withTrashed()->get();
	}

	private function formatDate($date)
	{
		if (!$date) {
			return null;
		}
		return Carbon::parse($date);
	}
}
