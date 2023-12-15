<?php

namespace App\Http\Controllers;

use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Elliptic\EC;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use kornrunner\Keccak;

class Web3LoginController extends Controller
{
    //
    public function message(): string
    {
        $nonce = Str::random();
        $message = "Sign this message to confirm you own this wallet address. This action will not cost any gas fees.\n\nNonce: " . $nonce;

        session()->put('sign_message', $message);

        return $message;
    }

    public function verify(Request $request): string
    {
        $result = $this->verifySignature(session()->pull('sign_message'), $request->input('signature'), $request->input('address'));
        // If $result is true, perform additional logic like logging the user in, or by creating an account if one doesn't exist based on the Ethereum address
        if ($result) {
            $address = $request->input('address');
            //check

            $user = User::where('real_wallet', $address)->first();
            if ($user) {
                Auth::loginUsingId($user->id);
            } else {
                $newUser = new User();
                $newUser->real_wallet = $address;
                $newUser->username = $address;
                $newUser->save();

                $sponsor = session()->get('sponsor');
                if ($sponsor != null) {
                    $sponsorId = User::where('username', $sponsor)->first();
                    $i = 2;
                    $refId = $sponsorId->referral_id;
                    while($i >= 2 && $i <= 6 && $refId) {
                        // Start find F2 of what referral
                        $userRef = User::find($refId);

                        if($userRef != null) {
                            switch($i) {
                                case 2:
                                    $f2_of = $userRef->id;
                                    break;
                                case 3:
                                    $f3_of = $userRef->id;
                                    break;
                                case 4:
                                    $f4_of = $userRef->id;
                                    break;
                                case 5:
                                    $f5_of = $userRef->id;
                                    break;
                                case 6:
                                    $f6_of = $userRef->id;
                                    break;
                                default:
                                    $test = 0;
                            }

                            $refId = $userRef->referral_id;
                            $i++;

                        }  else {
                            $i = 0;
                        }
                    }
                } else {
                    $sponsorId = null;
                }

                $newUser->f1_of = ($sponsorId != null) ? $sponsorId->id : null;
                $newUser->f2_of = $f2_of ?? null;
                $newUser->f3_of = $f3_of ?? null;
                $newUser->f4_of = $f4_of ?? null;
                $newUser->f5_of = $f5_of ?? null;
                $newUser->f6_of = $f6_of ?? null;
                $newUser->referral_id = ($sponsorId != null) ? $sponsorId->id : null;
                $newUser->save();

                //create wallet
                $client = new Client();
                $apiKey = Env::get('API_X_KEY');
                $apiUrl = Env::get('API_WALLET_URL').'create-wallet';
                try {
                    $client->request('POST', $apiUrl, [
                        'headers' => [
                            'Content-Type' => 'application/json',
                            'x-api-key' => $apiKey
                        ],
                        'body' => json_encode([
                            'id' => $newUser->id,
                        ])
                    ]);// Url of your choosing
                } catch (\Exception $e) {

                }

                Auth::loginUsingId($newUser->id);
            }
        }
        return $result;
    }

    protected function verifySignature(string $message, string $signature, string $address): bool
    {
        $hash = Keccak::hash(sprintf("\x19Ethereum Signed Message:\n%s%s", strlen($message), $message), 256);
        $sign = [
            'r' => substr($signature, 2, 64),
            's' => substr($signature, 66, 64),
        ];
        $recid = ord(hex2bin(substr($signature, 130, 2))) - 27;

        if ($recid != ($recid & 1)) {
            return false;
        }

        $pubkey = (new EC('secp256k1'))->recoverPubKey($hash, $sign, $recid);
        $derived_address = '0x' . substr(Keccak::hash(substr(hex2bin($pubkey->encode('hex')), 1), 256), 24);

        return (Str::lower($address) === $derived_address);
    }
}
