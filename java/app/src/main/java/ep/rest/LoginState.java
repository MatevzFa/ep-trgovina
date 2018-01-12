package ep.rest;

import java.io.Serializable;

/**
 * Created by matevz on 11.1.2018.
 */

public class LoginState implements Serializable {

    public String ime;
    public String priimek;
    public String token;
    public boolean loggedIn;

    @Override
    public String toString() {
        return ime + " " + priimek;
    }
}
