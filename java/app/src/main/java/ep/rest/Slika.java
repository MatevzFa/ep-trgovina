package ep.rest;

import java.io.Serializable;

/**
 * Created by matevz on 11.1.2018.
 */

public class Slika implements Serializable {
    public String path;
    public int id;

    @Override
    public String toString() {
        return path;
    }
}