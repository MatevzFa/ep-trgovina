package ep.rest;

import android.app.Activity;
import android.os.AsyncTask;
import android.os.Bundle;
import android.preference.PreferenceManager;
import android.support.annotation.Nullable;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

/**
 * Created by matevz on 12.1.2018.
 */

public class PrijavaActivity extends Activity {

    private EditText etEmail;
    private EditText etGeslo;
    private Button prijava;

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_prijava);

        etEmail = findViewById(R.id.et_email);
        etGeslo = findViewById(R.id.et_geslo);

        prijava = findViewById(R.id.btn_prijava_action);
        prijava.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(final View view) {
                TrgovinaService.getInstance()
                        .prijava(etEmail.getText().toString(), etGeslo.getText().toString())
                        .enqueue(new Callback<LoginState>() {
                            @Override
                            public void onResponse(Call<LoginState> call, Response<LoginState> response) {
                                if (response.body().loggedIn) {
                                    setToken(response);
                                    setResult(RESULT_OK, null);
                                    Log.i("PRIJAVA", response.body().token);
                                    finish();
                                } else recreate();
                            }

                            @Override
                            public void onFailure(Call<LoginState> call, Throwable t) {
                                Log.w("PRIJAVA", "failure");
                            }
                        });
            }
        });
    }

    private void setToken(Response<LoginState> response) {
        PreferenceManager
                .getDefaultSharedPreferences(getApplicationContext())
                .edit()
                .putString("token", response.body().token)
                .apply();
    }
}
