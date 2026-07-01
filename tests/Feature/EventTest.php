<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Event;
use App\Models\Country;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class EventTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * Test manage-events index page requires authentication.
     */
    public function test_manage_events_index_requires_authentication(): void
    {
        $response = $this->get(route('manage-events.index'));
        $response->assertRedirect('/login');
    }

    /**
     * Test manage-events index page loads for authenticated user.
     */
    public function test_manage_events_index_loads_for_authenticated_user(): void
    {
        $user = User::first();
        if (!$user) {
            $user = User::create([
                'username' => 'testuser',
                'userpass' => bcrypt('password'),
                'emailid' => 'test@example.com',
                'userlevel' => 1,
            ]);
        }

        $response = $this->actingAs($user)->get(route('manage-events.index'));
        $response->assertStatus(200);
        $response->assertViewIs('backend.manage-events.index');
        $response->assertViewHas('events');
    }

    /**
     * Test event update validation and success.
     */
    public function test_event_update_validation_and_success(): void
    {
        $user = User::first();
        if (!$user) {
            $user = User::create([
                'username' => 'testuser',
                'userpass' => bcrypt('password'),
                'emailid' => 'test@example.com',
                'userlevel' => 1,
            ]);
        }

        $event = Event::first();
        if (!$event) {
            $event = Event::create([
                'eventname' => 'Test Event',
                'eventcountry' => 1,
                'eventstart' => now()->toDateTimeString(),
                'eventend' => now()->addDays(2)->toDateTimeString(),
                'democc' => '',
                'prsurveycc' => '',
                'leadgencc' => '',
                'jumpstartcc' => '',
                'livewirejumpstartcc' => '',
                'livewireleadgencc' => '',
            ]);
        }

        // Test validation failure
        $response = $this->actingAs($user)->put(route('manage-events.update', $event->eventid), [
            'EventID' => $event->eventid,
            'EventName' => '', // empty is invalid
        ]);
        $response->assertSessionHasErrors(['EventName']);

        // Test validation success
        $response = $this->actingAs($user)->put(route('manage-events.update', $event->eventid), [
            'EventID' => $event->eventid,
            'EventName' => 'Updated Test Event Name',
            'EventCountry' => $event->eventcountry,
            'EventStartDate' => '07/02/2026',
            'EventEndDate' => '07/04/2026',
            'alloweventpreregistrations' => '1',
            'EventReminderTemp1' => '07/01/2026',
            'EventReminderTemp2' => '07/01/2026',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('events', [
            'eventid' => $event->eventid,
            'eventname' => 'Updated Test Event Name',
        ]);
    }

    /**
     * Test event create page loads for authenticated user.
     */
    public function test_event_create_loads_for_authenticated_user(): void
    {
        $user = User::first();
        if (!$user) {
            $user = User::create([
                'username' => 'testuser',
                'userpass' => bcrypt('password'),
                'emailid' => 'test@example.com',
                'userlevel' => 1,
            ]);
        }

        $response = $this->actingAs($user)->get(route('manage-events.create'));
        $response->assertStatus(200);
        $response->assertViewIs('backend.manage-events.create');
        $response->assertViewHasAll(['users', 'countries', 'dealers', 'waivers', 'trucks', 'surveys', 'emailTemplates']);
    }

    /**
     * Test event store successfully inserts new event and updates user permissions.
     */
    public function test_event_store_creates_event_and_updates_user_permissions(): void
    {
        $user = User::first();
        if (!$user) {
            $user = User::create([
                'username' => 'testuser',
                'userpass' => bcrypt('password'),
                'emailid' => 'test@example.com',
                'userlevel' => 1,
            ]);
        }

        $country = Country::first();
        if (!$country) {
            $country = Country::create([
                'countryname' => 'United States',
            ]);
        }

        $eventName = 'Wizard Test Event ' . uniqid();
        $response = $this->actingAs($user)->post(route('manage-events.store'), [
            'EventName' => $eventName,
            'EventCountry' => $country->countryid,
            'EventStartDate' => '07/10/2026',
            'EventEndDate' => '07/12/2026',
            'UserID' => [$user->userid],
        ]);

        $response->assertRedirect(route('manage-events.index'));
        $this->assertDatabaseHas('events', [
            'eventname' => $eventName,
        ]);

        $createdEvent = Event::where('eventname', $eventName)->first();
        $this->assertNotNull($createdEvent);

        // Verify user has the event ID in serialized allowevents
        $user->refresh();
        $allowEvents = unserialize($user->allowevents);
        $this->assertIsArray($allowEvents);
        $this->assertContains($createdEvent->eventid, $allowEvents);
    }
}
