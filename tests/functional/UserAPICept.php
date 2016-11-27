<?php
/**
 * Created by PhpStorm.
 * User: entry
 * Date: 27.11.2016
 * Time: 12:42
 * @var $scenario \Codeception\Scenario
 */

$I = new FunctionalTester($scenario);
$I->wantTo('check if api methods are working fine');

$I->amGoingTo('fetch list of groups');
$I->sendGET('groups');
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();

$I->amGoingTo('create a new group');
$I->sendPOST('groups',['name'=>'testGroup']);
$I->seeResponseCodeIs(201);
$I->seeResponseIsJson();

$groupId=($I->grabDataFromResponseByJsonPath('$.id'))[0];
$groupPath='groups/'.$groupId;

$I->amGoingTo('fetch info of a group');
$I->sendGET($groupPath);
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();

$I->amGoingTo('modify group info');
$I->sendPUT($groupPath,['name'=>'SouthParkUsers']);
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();

$I->amGoingTo('fetch list of users');
$I->sendGET('users');
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();

$I->amGoingTo('create a new user');
$I->sendPOST('users',[
    'email'=>'ericcartman@southpark.com',
    'firstName'=>'Eric',
    'lastName'=>'Cartman'
]);
$I->seeResponseCodeIs(201);
$I->seeResponseIsJson();
$userId=($I->grabDataFromResponseByJsonPath('$.id'))[0];
$userPath='users/'.$userId;

$I->amGoingTo('modify user info');
$I->sendPUT($userPath,[
    'firstName'=>'Kenny',
    'lastName'=>'McCormick',
    'email'=>'kennymccormick@southpark.com',
    'groupId'=>$groupId
]);
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();

$usersArray=[];

$I->amGoingTo('create some more users');
foreach ([
             [
                 'email'=>'KyleBroflovski@southpark.com',
                 'firstName'=>'Kyle',
                 'lastName'=>'Broflovski',
                 'groupId'=>$groupId
             ],
             [
                 'email'=>'StanMarsh@southpark.com',
                 'firstName'=>'Stan',
                 'lastName'=>'Marsh',
                 'groupId'=>$groupId
             ],
             [
                 'email'=>'JeromeMcElroy@southpark.com',
                 'firstName'=>'Jerome',
                 'lastName'=>'McElroy',
                 'groupId'=>$groupId
             ],
             [
                 'email'=>'ericcartman@southpark.com',
                 'firstName'=>'Eric',
                 'lastName'=>'Cartman',
                 'groupId'=>$groupId
             ],
         ] as $item){
    $I->sendPOST('users',$item);
    $I->seeResponseCodeIs(201);
    $I->seeResponseIsJson();
    $usersArray[]='users/'.($I->grabDataFromResponseByJsonPath('$.id'))[0];
}

$I->amGoingTo('change the state of all users to zero');
foreach (array_merge($usersArray,[$userPath]) as $item) {
    $I->sendPUT($item,['state'=>0]);
    $I->seeResponseCodeIs(200);
    $I->seeResponseIsJson();
}

$I->amGoingTo('make some unusual things. Let\'s try to kill everyone except Kenny :)');
foreach ($usersArray as $item){
    $I->sendDELETE($item);
    $I->seeResponseCodeIs(204);
    $I->seeResponseEquals(null);
}

$I->amGoingTo('check if we can use non-email-string');
$I->sendPUT($userPath,[
    'email'=>'(o_O)'
]);
$I->seeResponseCodeIs(422);
$I->seeResponseIsJson();

$I->amGoingTo('check if we can create a user with non-unique email');
$I->sendPOST('users',[
    'email'=>'kennymccormick@southpark.com',
    'firstName'=>'RandomName',
    'lastName'=>'RandomLastName'
]);
$I->seeResponseCodeIs(422);
$I->seeResponseIsJson();

$I->amGoingTo('check if we can delete a group that is currently linked to some user');
$I->sendDELETE($groupPath);
$I->seeResponseCodeIs(500);
$I->seeResponseIsJson();