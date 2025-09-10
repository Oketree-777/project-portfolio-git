<?php

namespace Database\Factories;

use App\Models\PersonalInfo;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PersonalInfo>
 */
class PersonalInfoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PersonalInfo::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faculties = [
            'คณะวิศวกรรมศาสตร์',
            'คณะวิทยาศาสตร์',
            'คณะศิลปศาสตร์',
            'คณะบริหารธุรกิจ',
            'คณะมนุษยศาสตร์',
            'คณะสังคมศาสตร์',
            'คณะแพทยศาสตร์',
            'คณะทันตแพทยศาสตร์',
            'คณะเภสัชศาสตร์',
            'คณะพยาบาลศาสตร์'
        ];

        $majors = [
            'วิศวกรรมคอมพิวเตอร์',
            'วิศวกรรมไฟฟ้า',
            'วิศวกรรมเครื่องกล',
            'วิศวกรรมเคมี',
            'วิศวกรรมโยธา',
            'วิทยาการคอมพิวเตอร์',
            'เทคโนโลยีสารสนเทศ',
            'คณิตศาสตร์',
            'ฟิสิกส์',
            'เคมี',
            'ชีววิทยา',
            'ภาษาอังกฤษ',
            'ภาษาไทย',
            'การจัดการ',
            'การตลาด',
            'การเงิน',
            'บัญชี',
            'เศรษฐศาสตร์',
            'รัฐศาสตร์',
            'สังคมวิทยา'
        ];

        return [
            'user_id' => User::factory(),
            'title_th' => $this->faker->sentence(3, 6),
            'title_en' => $this->faker->sentence(3, 6),
            'description' => $this->faker->paragraph(3, 5),
            'faculty' => $this->faker->randomElement($faculties),
            'major' => $this->faker->randomElement($majors),
            'year' => $this->faker->numberBetween(2020, 2024),
            'semester' => $this->faker->numberBetween(1, 3),
            'gpa' => $this->faker->randomFloat(2, 2.0, 4.0),
            'subject_gpa' => $this->faker->randomFloat(2, 2.0, 4.0),
            'photo' => null,
            'is_approved' => $this->faker->randomElement([true, false, null]),
            'approved_by' => null,
            'approved_at' => null,
            'rejection_reason' => null,
        ];
    }

    /**
     * Indicate that the portfolio is approved.
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_approved' => true,
            'approved_by' => User::factory()->create(['is_admin' => true])->id,
            'approved_at' => now(),
        ]);
    }

    /**
     * Indicate that the portfolio is pending approval.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_approved' => null,
            'approved_by' => null,
            'approved_at' => null,
        ]);
    }

    /**
     * Indicate that the portfolio is rejected.
     */
    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_approved' => false,
            'approved_by' => User::factory()->create(['is_admin' => true])->id,
            'approved_at' => now(),
            'rejection_reason' => $this->faker->sentence(),
        ]);
    }

    /**
     * Indicate that the portfolio has a photo.
     */
    public function withPhoto(): static
    {
        return $this->state(fn (array $attributes) => [
            'photo' => 'portfolio-photos/' . $this->faker->image('public/storage/portfolio-photos', 640, 480, null, false),
        ]);
    }

    /**
     * Indicate that the portfolio is for a specific faculty.
     */
    public function forFaculty(string $faculty): static
    {
        return $this->state(fn (array $attributes) => [
            'faculty' => $faculty,
        ]);
    }

    /**
     * Indicate that the portfolio is for a specific major.
     */
    public function forMajor(string $major): static
    {
        return $this->state(fn (array $attributes) => [
            'major' => $major,
        ]);
    }

    /**
     * Indicate that the portfolio is for a specific year.
     */
    public function forYear(int $year): static
    {
        return $this->state(fn (array $attributes) => [
            'year' => $year,
        ]);
    }

    /**
     * Indicate that the portfolio is for a specific semester.
     */
    public function forSemester(int $semester): static
    {
        return $this->state(fn (array $attributes) => [
            'semester' => $semester,
        ]);
    }
}
