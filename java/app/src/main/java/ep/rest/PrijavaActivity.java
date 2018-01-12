package ep.rest;

import android.app.Activity;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;

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
            public void onClick(View view) {
                new LoginTask(getApplicationContext())
                        .execute(etEmail.getText().toString(), etGeslo.getText().toString());
            }
        });
    }
}
