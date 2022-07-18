<?php

namespace Adldap\Auth;

use Adldap\Configuration\DomainConfiguration;
use Adldap\Connections\ConnectionInterface;

interface GuardInterface
{
    /**
     * Constructor.
     *
     * @param ConnectionInterface $connection
     * @param DomainConfiguration $configuration
     */
    public function __construct(ConnectionInterface $connection, DomainConfiguration $configuration);

    /**
     * Authenticates a user using the specified credentials.
     *
     * @param string $username The users LDAP username.
     * @param string $password The users LDAP password.
     * @param bool $bindAsUser Whether or not to bind as the user.
     *
     * @return bool
     * @throws \Adldap\Auth\UsernameRequiredException When username is empty.
     * @throws \Adldap\Auth\PasswordRequiredException When password is empty.
     *
     * @throws \Adldap\Auth\BindException             When re-binding to your LDAP server fails.
     */
    public function attempt($username, $password, $bindAsUser = false);

    /**
     * Binds to the current connection using the inserted credentials.
     *
     * @param string|null $username
     * @param string|null $password
     *
     * @return void
     * @throws \Adldap\Connections\ConnectionException If upgrading the connection to TLS fails
     *
     * @throws \Adldap\Auth\BindException              If binding to the LDAP server fails.
     */
    public function bind($username = null, $password = null);

    /**
     * Binds to the current LDAP server using the
     * configuration administrator credentials.
     *
     * @return void
     * @throws \Adldap\Auth\BindException When binding as your administrator account fails.
     *
     */
    public function bindAsAdministrator();
}
