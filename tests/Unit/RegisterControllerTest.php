<?php

namespace Tests\Unit;

use App\Models\BannedDomain;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var array
     */
    private $userData = [
        'name' => 'Pavlo',
        'email' => 'pavlo@email.com',
        'password' => 'qwerty',
    ];

    /**
     * @var string
     */
    private $registerUrl = '/api/register';

    /**
     * @var string
     */
    private $usersTable = 'users';

    /**
     * @var string
     */
    private $bannedDomainsTable = 'banned_domains';


    public function testUserCanRegister(): void
    {
        $this
            ->post($this->registerUrl, $this->userData)
            ->assertCreated();

        $this->assertDatabaseHas(
            $this->usersTable,
            $this->getFieldsToCheckUserExistence($this->userData)
        );
    }

    public function testUserCantRegisterWithoutName(): void
    {
        $data = $this->userData;

        unset($data['name']); // remove Name from input params

        $this
            ->post($this->registerUrl, $data)
            ->assertStatus(Response::HTTP_BAD_REQUEST);

        $this->assertDatabaseMissing(
            $this->usersTable,
            $this->getFieldsToCheckUserExistence($data)
        );
    }

    public function testUserCantRegisterWithShortName(): void
    {
        $data = [
            'name' => Str::random(1),
            'email' => $this->userData['email'],
        ];

        $this
            ->post($this->registerUrl, $data)
            ->assertStatus(Response::HTTP_BAD_REQUEST);

        $this->assertDatabaseMissing(
            $this->usersTable,
            $this->getFieldsToCheckUserExistence($data)
        );
    }

    public function testUserCantRegisterWithLongName(): void
    {
        $data = [
            'name' => Str::random(51),
            'email' => $this->userData['email'],
        ];

        $this
            ->post($this->registerUrl, $data)
            ->assertStatus(Response::HTTP_BAD_REQUEST);

        $this->assertDatabaseMissing(
            $this->usersTable,
            $this->getFieldsToCheckUserExistence($data)
        );
    }

    public function testUserCantRegisterWithoutEmail(): void
    {
        $data = $this->userData;

        unset($data['email']); // remove Email from input params

        $this
            ->post($this->registerUrl, $data)
            ->assertStatus(Response::HTTP_BAD_REQUEST);

        $this->assertDatabaseMissing(
            $this->usersTable,
            $this->getFieldsToCheckUserExistence($data)
        );
    }

    public function testUserCantRegisterWithWrongEmail(): void
    {
        $data = [
            'name' => $this->userData['name'],
            'email' => Str::random(1),
        ];

        $this
            ->post($this->registerUrl, $data)
            ->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);

        $this->assertDatabaseMissing(
            $this->usersTable,
            $this->getFieldsToCheckUserExistence($data)
        );
    }

    public function testUserCantRegisterWithLongEmail(): void
    {
        $data = [
            'name' => $this->userData['name'],
            'email' => Str::random(50).'@email.com',
        ];

        $this
            ->post($this->registerUrl, $data)
            ->assertStatus(Response::HTTP_BAD_REQUEST);

        $this->assertDatabaseMissing(
            $this->usersTable,
            $this->getFieldsToCheckUserExistence($data)
        );
    }

    public function testUserCantRegisterWithNonUniqueEmail(): void
    {
        $this->testUserCanRegister();

        $data = [
            'email' => $this->userData['email'],
            'name' => Str::random(5),
        ];

        $this
            ->post($this->registerUrl, $this->userData)
            ->assertStatus(Response::HTTP_BAD_REQUEST);

        $this->assertDatabaseMissing($this->usersTable, $data);
    }

    public function testUserCantRegisterWithDisabledIp(): void
    {
        $this
            ->post(
                $this->registerUrl,
                $this->userData,
                ['REMOTE_ADDR' => config('disabledIps')[0]]
            )
            ->assertStatus(Response::HTTP_FORBIDDEN);

        $this->assertDatabaseMissing(
            $this->usersTable,
            $this->getFieldsToCheckUserExistence($this->userData)
        );
    }

    public function testUserCantRegisterWithBannedDomain(): void
    {
        $this->testAddBannedDomain();

        $this
            ->post($this->registerUrl, $this->userData)
            ->assertStatus(Response::HTTP_BAD_REQUEST);

        $this->assertDatabaseMissing(
            $this->usersTable,
            $this->getFieldsToCheckUserExistence($this->userData)
        );
    }

    private function testAddBannedDomain(): void
    {
        $data = [
            'domain' => explode('@', $this->userData['email'])[1]
        ];

        factory(BannedDomain::class)->create($data);

        $this->assertDatabaseHas($this->bannedDomainsTable, $data);
    }

    /**
     * Return list of fields to check user existence in DB
     *
     * @param array $data
     * @return array
     */
    private function getFieldsToCheckUserExistence(array $data): array
    {
        unset($data['password']);

        return $data;
    }
}
