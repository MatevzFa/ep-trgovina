package ep.rest;

import android.content.Intent;
import android.os.Bundle;
import android.preference.PreferenceManager;
import android.support.v4.widget.SwipeRefreshLayout;
import android.support.v7.app.AppCompatActivity;
import android.util.Log;
import android.view.View;
import android.widget.AdapterView;
import android.widget.Button;
import android.widget.ListView;
import android.widget.TextView;
import android.widget.Toast;

import java.io.IOException;
import java.util.List;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class MainActivity extends AppCompatActivity implements Callback<List<Izdelek>> {
    private static final String TAG = MainActivity.class.getCanonicalName();

    private String token;

    private SwipeRefreshLayout container;
    private Button button;
    private ListView list;
    private IzdelekAdapter adapter;

    private Button prijava;
    private Button odjava;
    private TextView logindata;

    @Override
    protected void onCreate(Bundle savedInstanceState) {

        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        token = PreferenceManager.getDefaultSharedPreferences(getApplicationContext()).getString("token", null);
        Log.d(TAG, "" + token);
        list = (ListView) findViewById(R.id.items);

        adapter = new IzdelekAdapter(this);
        list.setAdapter(adapter);
        list.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> adapterView, View view, int i, long l) {
                final Izdelek izdelek = adapter.getItem(i);
                if (izdelek != null) {
                    final Intent intent = new Intent(MainActivity.this, IzdelekDetailActivity.class);
                    intent.putExtra("ep.rest.id", izdelek.id);
                    startActivity(intent);
                }
            }
        });

        container = (SwipeRefreshLayout) findViewById(R.id.container);
        container.setOnRefreshListener(new SwipeRefreshLayout.OnRefreshListener() {
            @Override
            public void onRefresh() {
                getApiInstance().getAll().enqueue(MainActivity.this);
            }
        });

//        button = (Button) findViewById(R.id.add_button);
//        button.setOnClickListener(new View.OnClickListener() {
//            @Override
//            public void onClick(View view) {
//                final Intent intent = new Intent(MainActivity.this, IzdelekFormActivity.class);
//                startActivity(intent);
//            }
//        });

        prijava = findViewById(R.id.btn_login);
        prijava.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                final Intent intent = new Intent(MainActivity.this, PrijavaActivity.class);
                startActivityForResult(intent, 1);
                recreate();
            }
        });
        odjava = findViewById(R.id.btn_logout);
        odjava.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                TrgovinaService.getInstance()
                        .odjava(token)
                        .enqueue(new Callback<LoginState>() {
                            @Override
                            public void onResponse(Call<LoginState> call, Response<LoginState> response) {
                                recreate();
                            }

                            @Override
                            public void onFailure(Call<LoginState> call, Throwable t) {
                                Log.w(TAG, "Logout fail");
                            }
                        });
            }
        });
        logindata = findViewById(R.id.logindata);

        getApiInstance().getAll().enqueue(MainActivity.this);
        if (token != null) {

            Log.i(TAG, token + "");
            getApiInstance()
                    .podatki(token)
                    .enqueue(new Callback<LoginState>() {
                        @Override
                        public void onResponse(Call<LoginState> call, Response<LoginState> response) {
                            logindata.setText(response.body().toString());
                        }

                        @Override
                        public void onFailure(Call<LoginState> call, Throwable t) {
                            PreferenceManager.getDefaultSharedPreferences(getApplicationContext()).edit().putString("token", null).apply();
                            recreate();
                        }
                    });
        }
    }

    @Override
    protected void onActivityResult(int requestCode, int resultCode, Intent data) {
        super.onActivityResult(requestCode, resultCode, data);
        if (resultCode == RESULT_OK) {
            recreate();
        }
    }

    @Override
    public void onResponse(Call<List<Izdelek>> call, Response<List<Izdelek>> response) {
        final List<Izdelek> hits = response.body();

        if (response.isSuccessful()) {
            Log.i(TAG, "Hits: " + hits.size());
            adapter.clear();
            adapter.addAll(hits);
        } else {
            String errorMessage;
            try {
                errorMessage = "An error occurred: " + response.errorBody().string();
            } catch (IOException e) {
                errorMessage = "An error occurred: error while decoding the error message.";
            }
            Toast.makeText(this, errorMessage, Toast.LENGTH_SHORT).show();
            Log.e(TAG, errorMessage);
        }
        container.setRefreshing(false);
    }

    @Override
    public void onFailure(Call<List<Izdelek>> call, Throwable t) {
        Log.w(TAG, "Error: " + t.getMessage(), t);
        container.setRefreshing(false);
    }

    public TrgovinaService.RestApi getApiInstance() {
        return TrgovinaService.getInstance(getApplicationContext());
    }
}
