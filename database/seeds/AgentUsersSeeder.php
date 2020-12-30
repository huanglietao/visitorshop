<?php

use Illuminate\Database\Seeder;

class AgentUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $agent = factory(App\Models\AgentUsers::class)->times(50)->make();
        App\Models\AgentUsers::insert($agent->toArray());
    }
}
