<?php

/**
 * This code was generated by
 * ___ _ _ _ _ _    _ ____    ____ ____ _    ____ ____ _  _ ____ ____ ____ ___ __   __
 *  |  | | | | |    | |  | __ |  | |__| | __ | __ |___ |\ | |___ |__/ |__|  | |  | |__/
 *  |  |_|_| | |___ | |__|    |__| |  | |    |__] |___ | \| |___ |  \ |  |  | |__| |  \
 *
 * Twilio - Verify
 * This is the public Twilio REST API.
 *
 * NOTE: This class is auto generated by OpenAPI Generator.
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */

namespace Twilio\Rest\Verify\V2\Service;

use Twilio\Exceptions\TwilioException;
use Twilio\ListResource;
use Twilio\Options;
use Twilio\Values;
use Twilio\Version;


class VerificationCheckList extends ListResource
    {
    /**
     * Construct the VerificationCheckList
     *
     * @param Version $version Version that contains the resource
     * @param string $serviceSid The SID of the verification [Service](https://www.twilio.com/docs/verify/api/service) to create the resource under.
     */
    public function __construct(
        Version $version,
        string $serviceSid
    ) {
        parent::__construct($version);

        // Path Solution
        $this->solution = [
        'serviceSid' =>
            $serviceSid,
        
        ];

        $this->uri = '/Services/' . \rawurlencode($serviceSid)
        .'/VerificationCheck';
    }

    /**
     * Create the VerificationCheckInstance
     *
     * @param array|Options $options Optional Arguments
     * @return VerificationCheckInstance Created VerificationCheckInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function create(array $options = []): VerificationCheckInstance
    {

        $options = new Values($options);

        $data = Values::of([
            'Code' =>
                $options['code'],
            'To' =>
                $options['to'],
            'VerificationSid' =>
                $options['verificationSid'],
            'Amount' =>
                $options['amount'],
            'Payee' =>
                $options['payee'],
            'SnaClientToken' =>
                $options['snaClientToken'],
        ]);

        $headers = Values::of(['Content-Type' => 'application/x-www-form-urlencoded' ]);
        $payload = $this->version->create('POST', $this->uri, [], $data, $headers);

        return new VerificationCheckInstance(
            $this->version,
            $payload,
            $this->solution['serviceSid']
        );
    }


    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString(): string
    {
        return '[Twilio.Verify.V2.VerificationCheckList]';
    }
}