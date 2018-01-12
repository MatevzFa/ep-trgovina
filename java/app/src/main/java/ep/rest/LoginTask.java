package ep.rest;

import android.app.Activity;
import android.content.Context;
import android.content.SharedPreferences;
import android.os.AsyncTask;
import android.preference.PreferenceManager;
import android.util.Log;
import android.widget.TextView;

import java.io.IOException;
import java.util.Arrays;

import retrofit2.Response;


/**
 * Created by matevz on 11.1.2018.
 */

public class LoginTask extends AsyncTask<String, Void, LoginState> {
    private static final String TAG = LoginTask.class.getCanonicalName();
    private TextView textView;

    private Context context;

    public LoginTask(Context context) {
        this.context = context;
    }

    @Override
    protected LoginState doInBackground(String... strings) {
        Response<LoginState> resp = null;
        Log.d(TAG, Arrays.toString(strings));
        try {
            resp = TrgovinaService.getInstance().prijava(strings[0], strings[1]).execute();
            Log.d(TAG, "onCreate: " + resp.body());
            if (resp.body().token != null)
                PreferenceManager.getDefaultSharedPreferences(context).edit().putString("token", resp.body().token).apply();

            return resp.body();
        } catch (IOException e) {
            Log.w(TAG, e.getMessage());
        }
        return null;
    }
}
