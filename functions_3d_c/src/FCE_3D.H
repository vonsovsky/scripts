#include <windows.h>
#include <math.h>
#include <stdio.h>
#include <shellapi.h>
#include <gl/gl.h>
#include <gl/glu.h>

#define ID_EDIT 100
#define ID_LIST 101
#define ID_ICON 102

float Convert(int, int, float);
void ExtractStrings(void);
int ControlExeChange(void);
void NaplnList(void);
void PrepisPredpis(void);
void SaveActive(void);
void LoadActive(void);
int CheckErrors(void);
int Nastav(char *, int, int);
