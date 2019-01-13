<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 13/1/19
 * Time: 6:41 PM
 */

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Validator;
use DateTime;

class MembersController extends Controller
{
    /**A Method to get all Members with pagination from database.
     * @return \Illuminate\Http\JsonResponse
     */
    public function displayMember()
    {
        $members = Member::orderBy('id', 'asc') -> paginate(10);
        return response()->json($members, 200);
    }

    /**A Method to count registered Members by year.
     * @return \Illuminate\Http\JsonResponse
     */
    public function countMembersByYear()
    {
        $yearsCount = [['Year', 'Registrations']];

        $firstYear = Member::min('joined_date');
        $firstYear = DateTime::createFromFormat("Y-m-d", $firstYear) ->format("Y");
        $lastYear = Member::max('joined_date');
        $lastYear = DateTime::createFromFormat("Y-m-d", $lastYear) ->format("Y");

        for($i=$firstYear; $i<(int)$lastYear+1; $i++) {
           $count = Member::whereYear('joined_date', $i)->count();
           $yearlyCount = [$i, $count];
           array_push($yearsCount, $yearlyCount);
        }
        return response()->json($yearsCount, 200);
    }


    /**search members by their firstname, surname, email address
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMember(Request $request)
    {
        $firstname = $request->firstname;
        $surname = $request->surname;
        $email = $request->email;

        if (is_null($firstname) && is_null($surname) && is_null($email)) {
            return response()->json("Please have at lease one search field.", 404);
        }

        if (!is_null($firstname) && !is_null($surname) && !is_null($email)) {
            $members = Member::where([['firstname', $firstname], ['surname', $surname], ['email', $email]])->get();
        } else {
            $members = Member::where('firstname', $firstname)->orWhere('surname', $surname)
                ->orWhere('email', $email)->get();
        }

        if (is_null($members)) {
            return response()->json("The member not found.", 404);
        }

        return response()->json($members, 200);
    }


    /**A method to add new members.
     * The method for me to import Ms SQL script into database.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function AddMember(Request $request)
    {
        $members = $request["members"];
        foreach ($members as $member) {
            //store the new movie into database
            $newMember = new Member;
            $newMember->firstname = $member["firstname"];
            $newMember->surname = $member["surname"];
            $newMember->email = $member["email"];
            $newMember->gender = $member["gender"];
            $newMember->joined_date = $member["joined_date"];
            $newMember->save();
        }
        return response()->json("New members added successfully. ", 200);
    }
}
